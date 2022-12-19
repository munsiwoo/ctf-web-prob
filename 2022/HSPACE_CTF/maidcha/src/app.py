import socket
import urllib.parse
import os.path

from response_form import *
from threading import Thread

urldecode = urllib.parse.unquote
def check_params(params):
    try:
        params = dict((urldecode(item.split('=')[0]),urldecode(item.split('=')[1])) for item in params.split('&'))
        if 'no' in params:
            if not params['no'].isdigit():
                return True
        return False
    except:
        pass

def make_response(req_data):
    try:
        method = req_data['method']
        req_uri = req_data['uri'].path
        qstring = req_data['uri'].query

        if check_params(qstring):
            return normal_response('403 Forbidden')
        if method not in ALLOWED_METHOD:
            return not_allow_method(ALLOWED_METHOD)

        if method == 'GET':
            params = urllib.parse.parse_qs(qstring)
            doc_path = DOCUMENT_DIR + '/' + os.path.basename(req_uri)

            if req_uri == '/read':
                if 'no' not in params:
                    content = "<p>Enter the 'no' parameter.</p>"
                else:
                    memo = MEMO_DIR + '/' + params['no'].pop()
                    if os.path.isfile(memo):
                        content = f"<h2>Memo</h2><small>{open(memo).read()}</small>"
                    else:
                        content = "<h2>Memo</h2><small>404 Not found</small>"
            else:
                if os.path.basename(req_uri) == '':
                    doc_path += 'index.html'

                if not os.path.isfile(doc_path):
                    return not_found()

                content = open(doc_path).read()
        return normal_response(content, 'text/html')

    except Exception as err:
        return internal_server_error(err)

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
    print('Closed', flush=True)
    return

if __name__ == '__main__':
    HOST, PORT = '0.0.0.0', 8080
    DOCUMENT_DIR = '/[CENSORED]/htdocs'
    MEMO_DIR = '/memo'
    ALLOWED_METHOD = ['GET']

    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    sock.bind((HOST, PORT))
    sock.listen()

    print(f"Server started {HOST}:{PORT}", flush=True)

    while True:
        try:
            (client, addr) = sock.accept()
            Thread(target=process, args=(client, addr, )).start()
            print('Connection', flush=True)

        except Exception as err:
            print(err, flush=True)
            break

    sock.close()