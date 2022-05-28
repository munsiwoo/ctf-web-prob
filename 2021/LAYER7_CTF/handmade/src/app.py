import socket
import urllib.parse
import os.path
import mimetypes

from response_form import *
from threading import Thread

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

        if method not in ALLOWED_METHOD:
            return not_allow_method(ALLOWED_METHOD)

        if method == 'GET':
            doc_path = DOCUMENT_DIR + req_uri

            if os.path.isdir(doc_path) and doc_path[::-1][0] != '/':
                doc_path += '/'
            if os.path.basename(doc_path) == '':
                doc_path += 'index.html'
            if not os.path.isfile(doc_path):
                return not_found()

            content = open(doc_path, 'rb').read()
            content_type = mimetypes.guess_type(doc_path)[0]

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

    client.send(res)
    client.close()

    print(addr[0], req_data['method'], req_data['uri'], flush=True)
    print('closed', flush=True)
    return

if __name__ == '__main__':
    HOST, PORT = '0.0.0.0', 8081
    DOCUMENT_DIR = '/service/htdocs'
    ALLOWED_METHOD = ['GET']

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