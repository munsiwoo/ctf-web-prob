def not_found():
    res = []
    res.append('HTTP/1.1 404 Not Found')
    res.append('Content-Type: text/html;charset=utf-8')
    res.append('Content-Length: 18')
    res.append('Connection: close')
    res.append('')
    res.append('<h2>Not found</h2>')

    res = '\r\n'.join(res)
    return res

def redirect(location='/'):
    res = []
    res.append('HTTP/1.1 301 Moved Permanently')
    res.append('Content-Type: text/html;charset=utf-8')
    res.append(f"Location: {location}")
    res.append('Content-Length: 26')
    res.append('Connection: close')
    res.append('')
    res.append('<h2>Moved Permanently</h2>')

    res = '\r\n'.join(res)
    return res

def bad_request():
    res = []
    res.append('HTTP/1.1 400 Bad Request')
    res.append('Content-Type: text/html;charset=utf-8')
    res.append('Content-Length: 18')
    res.append('Connection: close')
    res.append('')
    res.append('<h2>Bad Request</h2>')

    res = '\r\n'.join(res)
    return res

def not_allow_method(allow_method=['GET']):
    res = []
    res.append('HTTP/1.1 405 Method Not Allowed')
    res.append(f"Allow: {','.join(allow_method)}")
    res.append(f"Access-Control-Allow-Methods: {','.join(allow_method)}")
    res.append('Content-Length: 27')
    res.append('Content-Type: text/html;charset=utf-8')
    res.append('Connection: close')
    res.append('')
    res.append('<h2>Method Not Allowed</h2>')

    res = '\r\n'.join(res)
    return res

def internal_server_error(error_msg=""):
    res = []
    body = f"<h2>Internal Server Error</h2><small>{error_msg}</small>"

    res.append('HTTP/1.1 500 Internal Server Error')
    res.append(f"Content-Length: {len(body)}")
    res.append('Content-Type: text/html;charset=utf-8')
    res.append('Connection: close')
    res.append('')
    res.append(body)

    res = '\r\n'.join(res)
    return res

def normal_response(content, content_type='text/plain'):
    if content_type == None:
        content_type = 'text/plain'

    res = []
    res.append('HTTP/1.1 200 OK')
    res.append(f"Content-Length: {len(content)}")
    res.append(f"Content-Type: {content_type}")
    res.append('Connection: close')
    res.append('')
    res.append(str(content))

    res = '\r\n'.join(res)
    return res