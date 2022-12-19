import socket
import os.path
import urllib.parse
import requests
import ipaddress
import json
import re

from response_form import *
from mimetypes import guess_type
from threading import Thread
from model import read_notice, list_notice

requests.packages.urllib3.disable_warnings(
    requests.packages.urllib3.exceptions.InsecureRequestWarning
)

def check_params(params):
    try:
        if 'notice_id' in params:
            notice_id = params['notice_id'][0].lower()
            if not re.findall('^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$', notice_id):
                return True
        if 'url' in params:
            url = urllib.parse.unquote(params['url'][0])
            parse_url = urllib.parse.urlparse(url)
            if parse_url.scheme not in ['http', 'https']:
                return True
            if parse_url.hostname in ['localhost', 'admin.internal']:
                return True
            if ipaddress.ip_address(socket.gethostbyname(parse_url.hostname)).is_private:
                return True
        return False
    except:
        pass

def make_response(req_data):
    try:
        method = req_data['method']
        req_uri = req_data['uri'].path
        qstring = urllib.parse.parse_qs(req_data['uri'].query)

        if method not in ALLOWED_METHOD:
            return not_allow_method(ALLOWED_METHOD)
        if check_params(qstring):
            return normal_response('403 Forbidden')

        #---------------- Routing start [GET] ----------------#
        if method == 'GET':
            document = DOCUMENT_DIR + '/' + os.path.basename(req_uri)

            if os.path.basename(req_uri) == '':
                document += 'index.html'

            if not os.path.isfile(document):
                return not_found()

            content = open(document).read()
            content_type = guess_type(document)[0]
        
        #---------------- Routing start [POST] ----------------#
        if method == 'POST':
            params = urllib.parse.parse_qs(req_data['body'])
            content_type = "application/json"

            if check_params(params):
                return normal_response('403 Forbidden')

            if req_uri == '/notice/list':
                content = json.dumps(list_notice())
            
            elif req_uri == '/notice/read':
                if 'notice_id' not in params:
                    content = json.dumps({"status": False, "result": "Enter the \"notice_id\" parameter."})
                else:
                    notice_id = params['notice_id'][0].lower()
                    content = json.dumps(read_notice(notice_id))

            elif req_uri == '/proxy':
                if 'url' not in params:
                    content = json.dumps({"status": False, "result":"Enter the \"url\" parameter."})
                if 'method' not in params:
                    content = json.dumps({"status": False, "result":"Enter the \"method\" parameter."})
                else:
                    try:
                        url = params['url'][0].strip()
                        method = params['method'][0].strip()
                        data = {}
                        if method == 'post':
                            if 'data' in params:
                                data = json.loads(params['data'][0].strip())
                            proxy_response = requests.post(url, data=data, allow_redirects=False, verify=False, timeout=1).text
                        else:
                            proxy_response = requests.get(url, allow_redirects=False, verify=False, timeout=1).text
                        content = json.dumps({"status": True, "result": proxy_response})
                    except:
                        content = json.dumps({"status": False, "result": "Error (timeout, json parse)"})
            else:
                return not_found()
        #------------------- Routing end -------------------#
    except Exception as err:
        return internal_server_error(err)

    return normal_response(content, content_type)

def parse_http_request(req_data):
    req_data = req_data.split("\r\n" * 2)
    headers = req_data[0].split("\r\n")
    body = req_data[1]

    req_line = headers.pop(0).split(" ") # GET /foo HTTP/1.1
    retval = {
        "uri": urllib.parse.urlparse(req_line[1]),
        "method": req_line[0].upper(),
        "protocol": req_line[2],
        "headers": {},
        "body": body
    }

    for header in headers:
        header = header.split(":")
        key = header[0].strip()
        retval['headers'][key] = header[1].lstrip()

    return retval

def process(client, addr):
    try:
        req = client.recv(65535).decode()
        req_data = parse_http_request(req)
        res = make_response(req_data)
    except:
        res = bad_request()

    client.send(res.encode())
    client.close()

    print(addr[0], req_data['method'], req_data['uri'], flush=True)
    print("Disconnected.", flush=True)
    return

if __name__ == '__main__':
    HOST, PORT = "0.0.0.0", 8080
    
    DOCUMENT_DIR = "/app/htdocs"
    ALLOWED_METHOD = ['GET', 'POST']

    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    sock.bind((HOST, PORT))
    sock.listen()

    print(f"Server started - {HOST}:{PORT}", flush=True)

    while True:
        try:
            (client, addr) = sock.accept()
            Thread(target=process, args=(client, addr, )).start()
            print('Connected.', flush=True)

        except Exception as err:
            print(err, flush=True)
            break

    sock.close()