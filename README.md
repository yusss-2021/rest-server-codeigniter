# simple rest server using codeigniter

**table of contents**
1.List Endpoint<br>
2.deficiency<br>
3.browser prefilight handling<br>
4.Refrence<br>

## List Endpoint

// client
GET rest-server-codeingiter/
GET  rest-server-codeigniter/id
// admin
GET rest-server-codeigniter/admin/
POST rest-server-codeigniter/admin/
GET rest-server-codeigniter/admin/news/
DELETE rest-server-codeigniter/admin/news/id
GET rest-server-codeigniter/admin/news/id
PUT  rest-server-codeigniter/admin/news/id

## deficiency

1. No login autentification

## browser prefilight handling

Check the server if it supports cors and
prefilight handling on codeigniter

function options()
    {
        $this->output
            ->set_status_header(200)
            ->set_header("Access-Control-Allow-Origin:http://localhost:8080")
            ->set_header("Access-Control-Allow-Headers:Origin,Content-Type")
            ->set_header("Access-Control-Allow-Methods:Delete,PUT,OPTIONS")
            ->set_header("Origin")
            ->set_header("Access-Control-Request-Method")
            ->set_header("Access-Control-Request-Headers")
            ->_display();
        exit();
    }
    
 ## Refrence
 
 https://www.codecademy.com/articles/what-is-rest
 https://livebook.manning.com/book/cors-in-action/chapter-4/13
 https://fetch.spec.whatwg.org/#origin-header


