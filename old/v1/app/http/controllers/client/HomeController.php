<?php
    
    require_once __DIR__ . '/../Controller.php';
    require_once __DIR__ . '/../../../models/Tour.php';
    require_once __DIR__ . '/../../../models/Page.php';
    require_once __DIR__ . '/../../../models/Review.php';
    require_once __DIR__ . '/../../../models/Gallery.php';
    require_once __DIR__ . '/../../../models/SocialMedia.php';
    require_once __DIR__ . '/../../../models/Slide.php';
    require_once __DIR__ . '/../../../models/Service.php';
    require_once __DIR__ . '/../../../models/Video.php';
    require_once __DIR__ . '/../../../models/TourDetail.php';
    require_once __DIR__ . '/../../../models/TourHighlight.php';
    require_once __DIR__ . '/../../../models/TourPhoto.php';
    require_once __DIR__ . '/../../../models/TourPrice.php';
    require_once __DIR__ . '/../../../models/Info.php';
    require_once __DIR__ . '/../../../models/Content.php';
    require_once __DIR__ . '/../../../models/Blog.php';
    require_once __DIR__ . '/../../../models/Visitor.php';

    class HomeController extends Controller{

        private $tourModel;
        private $pageModel;
        private $reviewModel;
        private $galleryModel;
        private $socialMediaModel;
        private $slideModel;
        private $serviceModel;
        private $videoModel;
        private $tourDetailModel;
        private $tourHighlightModel;
        private $tourPhotoModel;
        private $tourPriceModel;
        private $infoModel;
        private $contentModel;
        private $blogModel;
        private $vars;
        private $prefix;
        private $view;
        private $is404;
        
        public function __construct($language = null)
        {
            parent::__construct($language);
            $this->tourModel = new Tour();
            $this->pageModel = new Page();
            $this->reviewModel = new Review();
            $this->galleryModel = new Gallery();
            $this->socialMediaModel = new SocialMedia();
            $this->slideModel = new Slide();
            $this->serviceModel = new Service();
            $this->videoModel = new Video();
            $this->tourDetailModel = new TourDetail();
            $this->tourHighlightModel = new TourHighlight();
            $this->tourPhotoModel = new TourPhoto();
            $this->tourPriceModel = new TourPrice();
            $this->infoModel = new Info();
            $this->contentModel = new Content();
            $this->blogModel = new Blog();
            $this->visitorModel = new Visitor();
            $this->is404 = false;

            $this->vars = [
                'tours' => $this->tourModel->all(),
                'info' => $this->infoModel->all()[0],
                'socialMedia' => $this->socialMediaModel->all()
            ];

            
            $this->prefix = 'client.pages.';
        }

        public function allPage() {
            return $this->pageModel->all();
        }

        public function home($language = null) {
            $this->vars['language'] = $language;
            $this->vars['slides'] = $this->slideModel->all();
            $this->vars['page'] = $this->pageModel->getByPath('');
            $this->vars['videos'] = $this->videoModel->all();
            $this->vars['contents'] = $this->contentModel->where('page', $this->vars['page']->path);
            $this->vars['services'] = $this->serviceModel->all();
            $this->vars['reviews'] = $this->reviewModel->limitOffset(4, 0, "WHERE pending='0'", 'DESC');
            $this->vars['galleries'] = $this->galleryModel->all();

            echo $this->view($this->view, $this->vars);
        }

        public function page($lang, $page, $params = null) {
            $landingPages = [
                'incoming-travel-agency-madagascar', 'madagascar-tour-guide', 
                'madagascar-travel-information', 'nosy-be-tours', 'madagascar-local-tour-operator'
            ];
            
            $this->vars['language'] = $lang;
            $this->vars['slides'] = $this->slideModel->all();
            $this->vars['page'] = $this->pageModel->getByPath($page);
            $this->vars['videos'] = $this->videoModel->all();
            $this->vars['contents'] = $this->contentModel->where('page', $this->vars['page']->path);
            $this->vars['services'] = $this->serviceModel->all();
            $this->vars['reviews'] = $this->reviewModel->limitOffset(3, 0, "WHERE pending='0'", 'DESC');
            $this->vars['galleries'] = $this->galleryModel->limitOffset(8, 0, "WHERE type=''", 'DESC');
            $this->view = $this->prefix.$page;
            if ($page == "") {

                // Visitor logging (exclude bots and repeat visits)
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                $botKeywords = ['bot', 'crawl', 'spider', 'slurp', 'curl', 'wget', 'python', 'java', 'facebook', 'pingdom', 'monitor', 'scrapy'];
                $isBot = false;
                foreach ($botKeywords as $keyword) {
                    if (stripos($userAgent, $keyword) !== false) {
                        $isBot = true;
                        break;
                    }
                }
                if (!$isBot) {
                    // Check for repeat visits from the same IP in the last 24 hours
                    $recentVisits = $this->visitorModel->getDb()->prepare("SELECT COUNT(*) FROM visitors WHERE ip_address = ? AND visited_at >= ?");
                    $recentVisits->execute([$ip, date('Y-m-d H:i:s', strtotime('-24 hours'))]);
                    $count = $recentVisits->fetchColumn();
                    if ($count == 0) {
                        
                        $results = [];
                        $url = "https://ipwho.is/".$ip;
                        $response = @file_get_contents($url);

                        if ($response !== false) {
                            $data = json_decode($response, true);
                            if (isset($data['success']) && $data['success']) {
                                $results = [
                                    'ip' => $ip,
                                    'city' => $data['city'],
                                    'region' => $data['region'],
                                    'country' => $data['country'],
                                    'country_code' => $data['country_code'],
                                    'latitude' => $data['latitude'],
                                    'longitude' => $data['longitude']
                                ];
                            } else {
                                $results = ['ip' => $ip, 'error' => 'Failed to get location'];
                            }
                        } else {
                            $results = ['ip' => $ip, 'error' => 'API request failed'];
                        }
                       //s $this->visitorModel->create($results);
                    }
                }
                $this->vars['tours_home'] = $this->tourModel->where('show_in_home', '1');
                $this->view = $this->prefix."index";
            } elseif ($page == "tours") {
                if(strpos($params, "_") !== false) {
                    $params = str_replace('_', '-', $params);
                    header('location: https://madagascar-green-tours.com/tours/'.$params);
                }
               $this->tours($params);
            } elseif ($page == "reviews") {
                $this->reviews();
            } elseif ($page == "gallery") {
                $this->vars['galleries'] = $this->galleryModel->all('DESC');
            }
             
            elseif ($page == "blog") {
                $this->blogs($params);
            } elseif ($page == "car-rental") {
                $this->vars['galleries'] = $this->galleryModel->where('type', 'car-rental');
            } elseif ($page == "hotel-booking") {
                $this->vars['galleries'] = $this->galleryModel->where('type', 'hotel-booking');
            } elseif ($page == "flight-booking") {
                $this->vars['galleries'] = $this->galleryModel->where('type', 'flight-booking');
            } elseif (in_array($page, $landingPages)) {
                $this->landing($lang, $page, $this->vars);
                return;
            }
            if ($this->is404) {
                http_response_code(404);
                echo $this->view($this->prefix."not-found", $this->vars);
            } else {
                echo $this->view($this->view, $this->vars);
            }
        }

        private function tours($params = null) {
            if ($params == null) {
                $this->view = $this->prefix."tours.all";
            } else {
                $tour = $this->tourModel->where('path', $params);
                if (count($tour) == 0) {
                    $this->is404 = true;
                    return ;
                }
                $tour = (object) $tour[0];
                $this->vars['page']->meta_title = $tour->meta_title;
                $this->vars['page']->meta_description = $tour->meta_description;
                $this->vars['page']->meta_keywords = $tour->meta_keywords;
                
                $this->vars['page']->meta_title_es = $tour->meta_title_es;
                $this->vars['page']->meta_description_es = $tour->meta_description_es;
                $this->vars['page']->meta_keywords_es = $tour->meta_keywords_es;
                
                $tour->highlights = $this->tourHighlightModel->where("name_tour", $tour->name);
                $prices  = $this->tourPriceModel->where("name_tour", $tour->name);
                $tour->details = $this->tourDetailModel->where("name_tour", $tour->name);
                $tour->photos = $this->tourPhotoModel->where("name_tour", $tour->name);
                foreach($prices as $prc) {
                    $type = 'price_'.strtolower($prc->type)."s";
                    $tour->$type[] = $prc; 
                }
                $this->vars['tour'] = $tour;
                $this->view = $this->prefix."tours.show";
            }
        }

        private function reviews() {
            $this->view = $this->prefix."reviews.all";
            if (currentPath() == "/reviews/new") {
                $this->view = $this->prefix."reviews.new";
            }
            if(isset($_GET['page'])) {
                $this->vars['p'] = $_GET['page'];
            } else {
                $this->vars['p'] = 1;
            }
            $reviewsConfirmed = $this->reviewModel->where('pending', '0');
            $this->vars['count'] = count($reviewsConfirmed);
            $limit = 4;
            $offset = ($this->vars['p']-1)*$limit;
            $this->vars['reviews'] = $this->reviewModel->limitOffset($limit, $offset, "WHERE pending='0'", 'DESC');
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // submit.php
                session_start();
                if ($_POST['token'] !== $_SESSION['token']) {
                    die("Invalid form submission.");
                }

                if($_POST['name'] == "Robertkag" || $_POST['name'] == "AnthonyErons") {
                    die("Invalid name");
                }
                
                $this->reviewModel->create([
                    'name_user' => $_POST['name'],
                    'email_user' => $_POST['email'],
                    'message' => $_POST['message'],
                    'rating' => $_POST['rating'],
                    'pending' => 1,
                    'daty' => date('Y-m-d H:i:s')
                ]);
                
                $lastReview = $this->reviewModel->all('DESC')[0];
            
                // Multiple recipients
                $to = 'info@madagascar-green-tours.com'; // note the comma
                // Subject
                $subject = 'Review of Madagascar Green Tours from your customer '.$_POST['name'];
                // Message
                $message = 'Review from : '.$_POST['name'].'<b><p>'.$_POST['message'].'</p>';
                $message .= '<a href="https://madagascar-green-tours.com/admin-panel/action?accept_review='.$lastReview->id.'" style="background: #06923E; padding: 4px; border-radius: 4px; color: #fff">Accept</a> ';
                $message .= '<a href="https://madagascar-green-tours.com/admin-panel/action?delete_review='.$lastReview->id.'" style="background: #E14434; padding: 4px; border-radius: 4px; color: #fff">Delete</a>';

                // To send HTML mail, the Content-type header must be set
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';

                // Additional headers
                $headers[] = 'To: <info@madagascar-green-tours.com>';
                $headers[] = 'From: Review <'.$_POST['email'].'>';

                $name = $_POST['name'];
                $email = $_POST['email'];
                require_once __DIR__ . '/../../utils/email_sender.php';
                if(sendEmail($name, $email, $subject, $message)) { 
                    $this->view = $this->prefix."reviews";
                    $this->vars['reviews'] = $this->reviewModel->limit($limit, 0, "WHERE pending='0'", 'DESC');
                    $this->vars['success'] = true;
                }
                // Mail it
                //mail($to, $subject, $message, implode("\r\n", $headers));
            }
        }

        private function blogs($params = null) {
            if ($params == null) {
                $this->vars['blogs'] = $this->blogModel->all("DESC");
                $this->view = $this->prefix."blogs.all";
            } else {
                $title = str_replace("-", " ", $params);
                $recentBlogs = $this->blogModel->limit(5);

                $blog = $this->blogModel->where('title', $title);
                if (count($blog) == 0) {
                    $this->is404 = true;
                    return;
                }
                $blog = (object) $blog[0];
                /*$blog->contents = $this->contentModel->where('blogs', $blog->id);*/
                $this->view = $this->prefix."blogs.show";
                $this->vars['blog'] = $blog;
                $this->vars['recentBlogs'] = $recentBlogs;
            }
        }


        public function landing($lang, $page, $vars) {  
            $this->view = $this->prefix."landing.".$page;
            echo $this->view($this->view, $vars);
        }
    }

        
?>