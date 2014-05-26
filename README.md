urlmask
=======

URLMASK
-------
"URLMASK" is URL shortening and masking system.
"URLMASK" contains 3 systems.

### API
* hash_url: converting raw URL to hash URL.
** ex: /urlmask/api/v1/hash_url/http://www.github.com/
* raw_url: converting hash URL to raw URL.
** ex: /urlmask/api/v1/raw_url/abcdefg

### WebUI
* "URLMASK" provides WebUI for using hash_url API.

### Redirector
* "URLMASK" can convert hash_url to raw_url and redirect raw_url.
** ex: access "/urlmask/rd/abcdefg", then "URLMASK" redirect "http://www.github.com/" automatically.


Contact
-------
Taku Unno@boscoworks <boscoworks[at]gmail.com>

