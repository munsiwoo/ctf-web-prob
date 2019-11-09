
## Margaret (Web - 250pts)  
#### solver : 2 (jinmoxjinmo123, JeonYoungSin)  

This challenge is `RCE` via `session lfi` challenge.  
I made a challenge for the `2018 layer7 ctf`.  

* Environment
	* Apache/2.4.18 (Ubuntu)
	* PHP 7.0.28-0ubuntu0.16.04.1
	* sqlite 3.11.0
-----------------
### php.ini - disable_functions
```
disable_functions = pcntl_alarm,pcntl_fork,pcntl_waitpid,pcntl_wait,pcntl_wifexited,pcntl_wifstopped,pcntl_wifsignaled,
pcntl_wifcontinued,pcntl_wexitstatus,pcntl_wtermsig,pcntl_wstopsig,pcntl_signal,pcntl_signal_dispatch,pcntl_get_last_error,
pcntl_strerror,pcntl_sigprocmask,pcntl_sigwaitinfo,pcntl_sigtimedwait,pcntl_exec,pcntl_getpriority,pcntl_setpriority,
system,passthru,exec,shell_exec
```
-----------------

Revenge version of this challenge is ready.  
Please contact me if you need revenge version.  
