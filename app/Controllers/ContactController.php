<?php

namespace Godsu\Mvc\Controllers;

use Godsu\Mvc\Models\Contact;
use Godsu\Mvc\Utility\cache\CacheConstruct;

class ContactController
{
    private $cache;
    private $contactModel;
    

    public function __construct()
    {
        $this->cache = CacheConstruct::createCache();
        $this->contactModel = new Contact();
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $subject = $_POST['subject'] ?? null;
            $message = $_POST['message'] ?? null;

            if ($name && $email && $subject && $message) {
                $this->contactModel->saveContact($name, $email, $subject, $message);

                $this->cache->deleteItem('contact_form');
                $this->cache->get('contact_form', function() use ($name) {
                    return "Thank you, $name! Your message has been submitted.";
                });

                echo "<p class='text-green-600'>Thank you! Your message has been submitted.</p>";
                return;
            } else {
                echo "<p class='text-red-600'>Please fill in all fields.</p>";
            }
        }
        

        require __DIR__ . '/../views/contact.php';
    }
}
