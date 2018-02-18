<?php
/**
 * Created by shalvah
 * Date: 17/02/2018
 * Time: 17:37
 */

namespace App\Services;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class UnnPortalScraper
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Cachemaster
     */
    private $cachemaster;

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var array
     */
    private $loginDetails = [];

    public function __construct(Cachemaster $cachemaster)
    {
        $this->client = $client = new Client();
        $this->cachemaster = $cachemaster;
    }

    public function login($username, $password)
    {
        $this->loginDetails = [$username, $password];
        
        $siteUrl = "http://unnportal.unn.edu.ng/";
        $this->crawler = $this->client->request('GET', $siteUrl);

        $loginFormFields = [
            '__EVENTVALIDATION' => $this->crawler->filter('#__EVENTVALIDATION')->attr('value'),
            '__VIEWSTATE' => $this->crawler->filter('#__VIEWSTATE')->attr('value'),
            '__VIEWSTATEGENERATOR' => $this->crawler->filter('#__VIEWSTATEGENERATOR')->attr('value'),
            'RadNotification1_ClientState' => '',
            'RadNotification1$hiddenState' => '',
            'RadNotification1_XmlPanel_ClientState' => '',
            'RadNotification1_TitleMenu_ClientState' => '',
            'inputUsername' => $username,
            'inputPassword' => $password,
            'login' => 'Login'
        ];

        $form = $this->crawler->selectButton('Login')->form($loginFormFields);
        $this->crawler = $this->client->submit($form);
        return $this->loginSucceeded();
    }
    
    public function extractDetails()
    {
        // visit profile page -- the home page uses iframes a lot,
        // so we can't just click on the button
        $this->crawler = $this->client->request('GET', 'http://unnportal.unn.edu.ng/modules/ProfileDetails/BioData.aspx');
        $data = $this->getDropdownFields() + $this->getTextInputFields();

        // keep it in the cache to speed up future responses
        $this->cachemaster->saveForStudent($this->loginDetails, $data);
        return $data;
    }

    /**
     * Check if the login was successful or not
     * Since UNN uses a JavaScript alert to inform of a failed login, we can't track that.
     * We can only check if we're still on the login form page
     *
     * @return bool
     */
    private function loginSucceeded()
    {
        $stillOnLoginPage = $this->crawler->filter('#inputUsername')->count()
            && $this->crawler->filter('#inputPassword')->count();
        return !$stillOnLoginPage;
    }

    /**
     * Get student profile fields rendered with dropdowns
     *
     * @return array
     */
    private function getDropdownFields()
    {
        $data = [];
        $fieldsUsingDropdowns = [
            'sex' => 'ContentPlaceHolder1_ddlSex',
            'department' => 'ContentPlaceHolder1_ddlDepartment',
            'entry_year' => 'ContentPlaceHolder1_ddlEntryYear',
            'grad_year' => 'ContentPlaceHolder1_ddlGradYear',
            'level' => 'ContentPlaceHolder1_ddlYearOfStudy',
        ];

        foreach ($fieldsUsingDropdowns as $field => $formFieldId) {
            $data[$field] = $this->crawler->filter("#$formFieldId")
                ->filterXPath('//option[@selected="selected"]')->text();
        }
        return $data;
    }

    /**
     * Get student profile fields rendered as text inputs
     *
     * @return array
     */
    private function getTextInputFields()
    {
        $data = [];
        $fieldsUsingTextInput = [
            'surname' => 'ContentPlaceHolder1_txtSurname',
            'first_name' => 'ContentPlaceHolder1_txtFirstname',
            'middle_name' => 'ContentPlaceHolder1_txtMiddlename',
            'mobile' => 'ContentPlaceHolder1_wmeMobileno',
            'email' => 'ContentPlaceHolder1_txtEmail',
            'matric_no' => 'ContentPlaceHolder1_txtMatricNo',
            'jamb_no' => 'ContentPlaceHolder1_txtJAMBNo',
        ];

        foreach ($fieldsUsingTextInput as $field => $formFieldId) {
            $data[$field] = $this->crawler->filter("#$formFieldId")->attr('value');
        }
        return $data;
    }

}
