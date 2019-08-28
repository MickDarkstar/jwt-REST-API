<?php
class IndexController extends BaseController
{
    public static function Home()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            echo json_encode(("derp: derpa"));
            http_response_code(200);
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            echo json_encode(("Not supported"));
            http_response_code(404);
        }
    }
}
