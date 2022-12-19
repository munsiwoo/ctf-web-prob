import socket
import os.path
import urllib.parse
import pymysql
import json

from response_form import *
from threading import Thread
from mimetypes import guess_type

from model import login, write_notice, check_token

def check_params(params):
    try:
        keys = ['username', 'password', 'title', 'contents']
        for key in keys:
            if key in params and ("'" in params[key][0] or '"' in params[key][0]):
                return True
        return False
    except:
        pass

def require_params(keys, params):
    for key in keys:
        if key not in params:
            return True
    return False

def make_response(req_data):
    try:
        method = req_data['method']
        req_uri = req_data['uri'].path
        qstring = urllib.parse.parse_qs(req_data['uri'].query)

        if method not in ALLOWED_METHOD:
            return not_allow_method(ALLOWED_METHOD)

        #---------------- Routing start [GET] ----------------#
        if method == 'GET':
            document = DOCUMENT_DIR + '/' + os.path.basename(req_uri)

            if os.path.basename(req_uri) == '':
                document += 'index.html'

            if not os.path.isfile(document):
                return not_found()

            if os.path.basename(req_uri) == 'write.html':
                if 'token' not in qstring:
                    return normal_response('unauthorized')
                if not check_token(qstring['token'][0]):
                    return normal_response('unauthorized')

            content = open(document).read()
            content_type = guess_type(document)[0]

        #---------------- Routing start [POST] ----------------#
        if method == 'POST':
            params = urllib.parse.parse_qs(req_data['body'])
            content_type = "application/json"

            if check_params(params):
                return normal_response('403 Forbidden')

            if req_uri == '/login':
                if require_params(['username', 'password'], params):
                    content = json.dumps({"status": False, "result": f"Enter the [username, password] parameters."})
                else:
                    username = params['username'][0][:15]
                    password = params['password'][0][:15]
                    content = json.dumps(login(username, password))

            elif req_uri == '/write_notice':
                if require_params(['title', 'contents', 'token'], params):
                    content = json.dumps({"status": False, "result": f"Enter the [title, contents, token] parameters."})
                elif not check_token(params['token'][0]):
                    return normal_response('unauthorized') 
                else:
                    title = params['title'][0][:30]
                    contents = params['contents'][0][:100]
                    content = json.dumps(write_notice(title, contents))

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
    HOST, PORT = "0.0.0.0", 80
    
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