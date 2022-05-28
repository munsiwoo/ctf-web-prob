import socket
import urllib.parse
import os.path
import mimetypes
import json
import re
import requests

from response_form import *
from threading import Thread

requests.packages.urllib3.disable_warnings(
    requests.packages.urllib3.exceptions.InsecureRequestWarning
)

def check_params(params):
    if params.strip() == '':
        return True
    params = dict((item.split('=')[0], item.split('=')[1]) for item in params.split('&'))

    if 'no' in params:
        if not params['no'].isdigit():
            return False
    if 'url' in params:
        if not (params['url'].startswith('http://') or params['url'].startswith('https://')):
            return False
    return True

def parse_http_request(req_data):
    req_data = req_data.split('\r\n\r\n')
    headers = req_data[0].split('\r\n')
    body = req_data[1]

    req_line = headers[0].split(' ') # GET /foo HTTP/1.1
    retval = {
        'method': req_line[0].upper(),
        'uri': urllib.parse.urlparse(req_line[1]),
        'protocol': req_line[2],
        'headers': {},
        'body': body
    }
    headers.pop(0)

    for header in headers:
        header = header.split(':')
        key = header[0].strip()
        retval['headers'][key] = header[1].lstrip()

    return retval

def make_response(req_data):
    try:
        method = req_data['method']
        req_uri = req_data['uri'].path
        qstring = req_data['uri'].query

        if not check_params(qstring):
            return normal_response('403 forbidden')
        if method not in ALLOWED_METHOD:
            return not_allow_method(ALLOWED_METHOD)

        if method == 'GET':
            doc_path = DOCUMENT_DIR + '/' + os.path.basename(req_uri)

            if os.path.basename(req_uri) == '':
                doc_path += 'index.html'

            if not os.path.isfile(doc_path):
                return not_found()

            content = open(doc_path).read()
            content_type = mimetypes.guess_type(doc_path)[0]

        if method == 'POST':
            post_body = req_data['body']

            if 'Content-Type' in req_data['headers']:
                if req_data['headers']['Content-Type'] == 'application/json':
                    body = json.loads(req_data['body'])
                    post_body = urllib.parse.urlencode(body)

            if not check_params(post_body):
                return normal_response('403 forbidden')

            data = urllib.parse.parse_qs(post_body)

            if req_uri == '/read':
                if 'no' not in data:
                    content = json.dumps({"message": "Enter the 'no' parameter."})
                    return normal_response(content, 'application/json')

                content_file = CONTENT_DIR + '/' + data['no'].pop()
                if not os.path.isfile(content_file):
                    return not_found()

                content = {"status": "ok", "result": open(content_file).read()}
                return normal_response(content, 'application/json')

            elif req_uri == '/proxy':
                if 'url' not in data:
                    content = json.dumps({"message": "Enter the 'url' parameter."})
                    return normal_response(content, 'application/json')

                try:
                    proxy_response = requests.get(data['url'].pop(), allow_redirects=False, verify=False, timeout=1).text
                    content = json.dumps({"status": "ok", "result": proxy_response})
                except:
                    content = json.dumps({"status": "fail", "result": "timeout"})
                return normal_response(content, 'application/json')

            else:
                return not_found()

    except Exception as err:
        return internal_server_error(err)

    return normal_response(content, content_type)

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
    print('closed', flush=True)
    return

if __name__ == '__main__':
    HOST, PORT = '0.0.0.0', 8080
    DOCUMENT_DIR = '/service/htdocs'
    CONTENT_DIR = '/service/contents'
    ALLOWED_METHOD = ['GET', 'POST']

    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    sock.bind((HOST, PORT))
    sock.listen()

    print(f"server started {HOST}:{PORT}", flush=True)

    while True:
        try:
            (client, addr) = sock.accept()
            Thread(target=process, args=(client, addr, )).start()
            print('connection', flush=True)

        except Exception as err:
            print(err, flush=True)
            break

    sock.close()