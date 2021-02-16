# simple rest server using codeigniter

**table of contents**<br>
1.List Endpoint<br>
2.deficiency<br>
3.browser prefilight handling<br>
4.Refrence<br>

## List Endpoint

// client<br>
GET rest-server-codeingiter/<br>
GET  rest-server-codeigniter/id<br>
// admin<br>
GET rest-server-codeigniter/admin/<br>
POST rest-server-codeigniter/admin/<br>
GET rest-server-codeigniter/admin/news/<br>
DELETE rest-server-codeigniter/admin/news/id<br>
GET rest-server-codeigniter/admin/news/id<br>
PUT  rest-server-codeigniter/admin/news/id<br>
<br>
## deficiency<br>

1. No login autentification<br>

## browser prefilight handling<br>

Check the server if it supports cors and<br>
prefilight handling on codeigniter<br>

function options()<br>
    {<br>
        $this->output<br>
            ->set_status_header(200)<br>
            ->set_header("Access-Control-Allow-Origin:http://localhost:8080")<br>
            ->set_header("Access-Control-Allow-Headers:Origin,Content-Type")<br>
            ->set_header("Access-Control-Allow-Methods:Delete,PUT,OPTIONS")<br>
            ->set_header("Origin")<br>
            ->set_header("Access-Control-Request-Method")<br>
            ->set_header("Access-Control-Request-Headers")<br>
            ->_display();<br>
        exit();<br>
    }<br>
    
 ## Refrence<br>
 
 https://www.codecademy.com/articles/what-is-rest<br>
 https://livebook.manning.com/book/cors-in-action/chapter-4/13<br>
 https://fetch.spec.whatwg.org/#origin-header<br>


