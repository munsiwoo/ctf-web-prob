# Power of Xx 2017

~~~
* 제작 : 문시우

* 문제명 : basic web

* 난이도 : 하

* 분야 : 웹
~~~
~~~
설명 : lfi + sqli (error based blind insert injection)
?p= 파라미터에서 lfi 취약점이 발생한다. php wrapper를 이용해 소스를 얻을 수 있다.
ex : ?p=php://filter/convert.base64-encode/resource=config

소스를 얻고 소스를 분석하여 sql injection 을 수행하면된다. (send.htm 에서 취약점 발생)
sql injection 으로 어드민의 패스워드를 알아냈다면 config.htm에 있는 secret_key와 얻어낸 아이디/패스워드로
어드민 계정으로 로그인해서 adm1n.htm로 접근하면 플래그를 얻을 수 있다.

payload :
email=1&title=1\&contents=,if((select substr(password,1,1) from users where 1)='t', (select 1 union select 2), 2));#

exploit : ./exploit.py
~~~
---------------------------------------
플래그 : flag{s1mple_SQL_1n73ction_XD}
