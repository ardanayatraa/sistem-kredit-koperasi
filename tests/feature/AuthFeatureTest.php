<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

class AuthFeatureTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Don't run events during testing
        $this->skipEvents();
    }

    public function testLoginAsBendahara()
    {
        // First get the login page to obtain CSRF token
        $getResponse = $this->get('/login');
        
        // Extract CSRF token from the form
        $body = $getResponse->getBody();
        preg_match('/<input[^>]+name="csrf_test_name"[^>]+value="([^"]+)"/', $body, $matches);
        $csrfToken = $matches[1] ?? '';
        
        $result = $this->withSession()
                       ->post('/login', [
                           'username' => 'bendahara',
                           'password' => 'bendahara123',
                           'csrf_test_name' => $csrfToken
                       ]);
        
        $result->assertStatus(302); // Redirect
        $result->assertRedirectTo('/dashboard-bendahara');
    }

    public function testLoginAsKetua()
    {
        // First get the login page to obtain CSRF token
        $getResponse = $this->get('/login');
        
        // Extract CSRF token from the form
        $body = $getResponse->getBody();
        preg_match('/<input[^>]+name="csrf_test_name"[^>]+value="([^"]+)"/', $body, $matches);
        $csrfToken = $matches[1] ?? '';
        
        $result = $this->withSession()
                       ->post('/login', [
                           'username' => 'ketua_koperasi',
                           'password' => 'ketua123',
                           'csrf_test_name' => $csrfToken
                       ]);
        
        $result->assertStatus(302); // Redirect
        $result->assertRedirectTo('/dashboard-ketua');
    }

    public function testLoginAsAppraiser()
    {
        // First get the login page to obtain CSRF token
        $getResponse = $this->get('/login');
        
        // Extract CSRF token from the form
        $body = $getResponse->getBody();
        preg_match('/<input[^>]+name="csrf_test_name"[^>]+value="([^"]+)"/', $body, $matches);
        $csrfToken = $matches[1] ?? '';
        
        $result = $this->withSession()
                       ->post('/login', [
                           'username' => 'appraiser',
                           'password' => 'appraiser123',
                           'csrf_test_name' => $csrfToken
                       ]);
        
        $result->assertStatus(302); // Redirect
        $result->assertRedirectTo('/dashboard-appraiser');
    }

    public function testLoginAsAnggota()
    {
        // First get the login page to obtain CSRF token
        $getResponse = $this->get('/login');
        
        // Extract CSRF token from the form
        $body = $getResponse->getBody();
        preg_match('/<input[^>]+name="csrf_test_name"[^>]+value="([^"]+)"/', $body, $matches);
        $csrfToken = $matches[1] ?? '';
        
        $result = $this->withSession()
                       ->post('/login', [
                           'username' => 'anggota_demo',
                           'password' => 'anggota123',
                           'csrf_test_name' => $csrfToken
                       ]);
        
        $result->assertStatus(302); // Redirect
        $result->assertRedirectTo('/dashboard-anggota');
    }

    public function testUnauthorizedAccessRedirectsToLogin()
    {
        $result = $this->get('/dashboard-bendahara');
        $result->assertRedirectTo('/login');
    }
}