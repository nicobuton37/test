<?php


namespace TLD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction()
    {
      $client = new Client([
        'base_uri' => 'https://api.github.com/',
        'timeout' => 2.0,
      ]);

      // default dates
      // $default_date = $this->setDefaultDate();

      // Retrieve commits
      $commits = $client->request('GET', 'repos/nicobuton37/cardioV2/commits');
      // dump(json_decode($commits->getBody()));

      $commits_list = json_decode($commits->getBody());

      // dates commits list
      $dates = [];

      foreach ($commits_list as $value) {
        array_push($dates, $value->commit->author->date);
      }

      $dates_converted = $this->convertDate($dates);

      // compare dates with default date, use diff() method
      // count commits foreach weeks
      // dump($default_date);
      return $this->render('TLDCoreBundle:Default:index.html.twig', ['dates' => $dates_converted]);
    }

    function convertDate($dates)
    {
      $dates_converted = [];
      foreach ($dates as $value) {
        $time = strtotime($value);
        $new_format = date('Y-m-d h:i:s', $time);
        array_push($dates_converted, $new_format);
      }

      return $dates_converted;
    }

    // function setDefaultDate()
    // {
    //   return $today->format('Y-m-d h:i:s');
    // }
}
