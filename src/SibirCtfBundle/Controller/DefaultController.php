<?php
namespace SibirCtfBundle\Controller;

use SibirCtfBundle\Entity\Mentor;
use SibirCtfBundle\Entity\News;
use SibirCtfBundle\Entity\Team;
use SibirCtfBundle\Entity\Speaker;
use SibirCtfBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
//        if (time() < 1512707400) {
            return $this->render('SibirCtfBundle::index.html.twig');
//        }
//        else {
//            return $this->render('SibirCtfBundle::index-live.html.twig');
//        }
    }

    /**
     * @Route("/liveversion", name="index-live")
     */
    public function liveAction()
    {
        return $this->render('SibirCtfBundle::index-live.html.twig');
    }

    /**
     * @Route("/participants", name="participants")
     */
    public function participantsAction(Request $request)
        {
            $repository = $this->getDoctrine()->getRepository('SibirCtfBundle:Team');

            $teamsApproved = $repository->findBy(array('status' => 1));
            $teamsPending = $repository->findBy(array('status' => 0));
            $teamsReject = $repository->findBy(array('status' => 2));

            return $this->render('SibirCtfBundle:17:participants.html.twig', array(
                'teamsApproved' => $teamsApproved,
                'teamsPending' => $teamsPending,
                'teamsReject' => $teamsReject
            ));
        }

    /**
     * @Route("/registration", name="signup")
     * @param Request $request
     *
     * @return Response
     */
    public function signupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $team = new Team();

        $form = $this->createForm('SibirCtfBundle\Form\TeamType', $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $someNewFilename = md5(microtime() + 'somesalt');

            $file = $form['logo']->getData();
            $extension = $file->guessExtension();

            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                $fileName = $someNewFilename.'.'.$extension;
                $file->move($this->getParameter('team_logo_directory'), $fileName);

                $team->setLogo($fileName);

                foreach($team->getMembers() as $key => $value) {
                    $value->setTeam($team);
                }

                $em->persist($team);
                $em->flush();

                $mailer = $this->get('mailer');
                $message = $mailer->createMessage()
                    ->setSubject($team->getTitle() . ' зарегистрировалась на SibirCTF')
                    ->setFrom('noreply@sibirctf.org')
                    ->setTo('mukovkin@yandex.ru')
                    ->setBody('', 'text/html');
                $mailer->send($message);

                return $this->redirectToRoute('registration_success');
            }
        }

        return $this->render('SibirCtfBundle:17:registration.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/registration/volunteer", name="registration-volunteer")
     * @param Request $request
     *
     * @return Response
     */
    public function signupVolunteer(Request $request)
    {
        return $this->render('SibirCtfBundle:cabinet:registration-volunteer.html.twig');
    }

    /**
     * @Route("/registration/all", name="signup_all")
     * @param Request $request
     *
     * @return Response
     */
    public function signupAllAction(Request $request)
    {
        return $this->render('SibirCtfBundle:cabinet:registration-select.html.twig');
    }

    /**
     * @Route("/timetable", name="timetable")
     */
    public function timetableAction()
    {
        $programs = array(
            'first' => array(
                'date' => mktime(0,0,0,12,8,2017),
                'name' => 'Первый день соревнований',
                'program' => array(
                    array(
                        'event_name' => 'Сбор гостей',
                        'date_start' => mktime(8,0,0),
                        'date_end' => mktime(9, 0, 0)
                    ),
                    array(
                        'event_name' => 'Открытие',
                        'date_start' => mktime(9,0,0),
                        'date_end' => mktime(10, 30, 0)
                    ),
                    array(
                        'event_name' => 'Часть 1: Task-based CTF',
                        'date_start' => mktime(10,0,0),
                        'date_end' => mktime(21, 0, 0)
                    ),
                    array(
                        'event_name' => 'Выставка',
                        'date_start' => mktime(10,0,0),
                        'date_end' => mktime(21, 0, 0)
                    ),
                    array(
                        'event_name' => 'Обед',
                        'date_start' => mktime(9,0,0),
                        'date_end' => mktime(10, 30, 0)
                    ),
                    array(
                        'event_name' => 'Отъезд гостей',
                        'date_start' => mktime(21,0,0),
                        'date_end' => mktime(21, 30, 0)
                    )
                ),
                'geo' => array(
                    'center' => '56.474264264322514,84.9509041999073',
                    'zoom' => 16,
                    'points' => array(
                        array(
                            'geo' => '56.45407571268017,84.978238799436',
                            'hintContent' => 'Место проведения первого дня соревнований',
                            'balloonContent' => 'Место проведения первого дня соревнований <br /> Красноармейская 146, корпус УЛК'
                        )
                    )
                )
            ),
            'second' => array(
                'date' => mktime(0,0,0,12,9,2017),
                'name' => 'Первый день соревнований',
                'program' => array(
                    array(
                        'event_name' => 'Сбор гостей',
                        'date_start' => mktime(9,0,0),
                        'date_end' => mktime(9, 30, 0)
                    ),
                    array(
                        'event_name' => 'Инструктаж',
                        'date_start' => mktime(9,0,0),
                        'date_end' => mktime(9, 30, 0)
                    ),
                    array(
                        'event_name' => 'Часть 2: Attack-defense',
                        'date_start' => mktime(10,0,0),
                        'date_end' => mktime(14, 0, 0)
                    ),
                    array(
                        'event_name' => 'Часть 2.1: Flag rush',
                        'date_start' => mktime(10,0,0),
                        'date_end' => mktime(14, 0, 0)
                    ),
                    array(
                        'event_name' => 'Выставка',
                        'date_start' => mktime(10,0,0),
                        'date_end' => mktime(14, 0, 0)
                    ),
                    array(
                        'event_name' => 'Обед',
                        'date_start' => mktime(9,0,0),
                        'date_end' => mktime(10, 30, 0)
                    ),
                    array(
                        'event_name' => 'Инструктаж',
                        'date_start' => mktime(14,45,0),
                        'date_end' => mktime(15, 0, 0)
                    ),
                    array(
                        'event_name' => 'Часть 3: Battle (полуфинал)',
                        'date_start' => mktime(15,0,0),
                        'date_end' => mktime(17, 0, 0)
                    ),
                    array(
                        'event_name' => 'Часть 3: Battle (финал)',
                        'date_start' => mktime(17,0,0),
                        'date_end' => mktime(19, 0, 0)
                    ),
                    array(
                        'event_name' => 'Часть 3.1: HackBOX',
                        'date_start' => mktime(15,0,0),
                        'date_end' => mktime(19, 0, 0)
                    ),
                    array(
                        'event_name' => 'Выставка с тасками',
                        'date_start' => mktime(15,0,0),
                        'date_end' => mktime(19, 0, 0)
                    ),
                    array(
                        'event_name' => 'Награждение',
                        'date_start' => mktime(19,0,0),
                        'date_end' => mktime(20, 0, 0)
                    ),
                    array(
                        'event_name' => 'After party',
                        'date_start' => mktime(21,0,0),
                        'date_end' => mktime(24, 0, 0)
                    )

                ),
                'geo' => array(
                    'center' => '56.45407571268017,84.978238799436',
                    'zoom' => 16,
                    'points' => array(
                        array(
                            'geo' => '56.45407571268017,84.978238799436',
                            'hintContent' => 'Место проведения первого дня соревнований',
                            'balloonContent' => 'Место проведения первого дня соревнований <br /> Красноармейская 146, корпус УЛК'
                        ),
                        array(
                            'geo' => '56.453231, 84.975775',
                            'hintContent' => 'Место проведения секретного мероприятия',
                            'balloonContent' => "Место проведения секретного мероприятия <br/> Красноармейская 147 (студенческий бизнес-инкубатор)"
                        )
                    )
                )
            )
        );
        return $this->render('SibirCtfBundle::timetable.html.twig', array(
            'programs' => $programs,
            'months' => array(
                '9' => 'сентября',
                '10' => 'октября',
                '12' => 'декабря'
            )
        ));
    }

    /**
     * @Route("/teams", name="teams")
     */
    public function teamsAction()
    {
        $organizing = array(
            array(
                'image' => 'pyarin.jpg',
                'person' => 'Пярин Виктор Анатольевич',
                'position' => 'Председатель оргкомитета',
            ),
            array(
                'image' => 'minin.jpg',
                'person' => 'Минин Виктор',
                'position' => 'МРОО «АРСИБ»',
            ),
            array(
                'image' => 'kiskachi.jpg',
                'person' => 'Кискачи Мария',
                'position' => 'Директор RuCTF',
            ),
            array(
                'image' => 'pedanov.jpg',
                'person' => 'Педанов Владимир',
                'position' => 'Советник Председателя Правления МРОО «АРСИБ» по правовым вопросам',
            ),
            array(
                'image' => 'ivanov.jpg',
                'person' => 'Иванов Антон',
                'position' => 'Руководитель направления «Технологии информационной безопасности»',
            ),
            array(
                'image' => 'khodakov.jpg',
                'person' => 'Ходаков Сергей',
                'position' => 'Директор по операционной работе. Кластер информационных технологий Инновационного Центра «Сколково»',
            ),
            array(
                'image' => 'sutormina.jpg',
                'person' => 'Сутормина Дарья',
                'position' => 'Финансовый директор',
            )
        );

        $teams = array(
            array(
                'image' => 'bekasova.jpg',
                'person' => 'Бекасова Мария',
                'position' => 'Руководитель проекта',
                'tg' => 'bekasova'
            ),
            array(
                'image' => 'uskov.jpg',
                'person' => 'Усков Александр',
                'position' => 'Технический директор',
                'tg' => 'uskoff'
            ),
            array(
                'image' => 'polina.jpg',
                'person' => 'Щедрина Полина',
                'position' => 'Главный редактор CTF News',
                'tg' => 'onneapolina'
            ),
            array(
                'image' => 'rodionov.jpg',
                'person' => 'Родионов Алексей',
                'position' => 'Разработчик Attack-defense',
                'tg' => 'menad',
                'team' => 'Espacio'
            ),
            array(
                'image' => 'smirnoff.jpg',
                'person' => 'Смирнов Максим',
                'position' => 'Разработчик Task-based и flag rush',
                'tg' => 'by_sm',
                'team' => 'Life'
            ),
            array(
                'image' => 'borodin.jpg',
                'person' => 'Бородин Дмитрий',
                'position' => 'Разработчик Battle CTF',
                'tg' => 'FadeDemon',
                'team' => 'Life'
            ),
            array(
                'image' => 'shlyndin.jpg',
                'person' => 'Шлюндин Павел',
                'position' => 'Наблюдатель',
                'tg' => 'Riocool',
                'team' => 'Honeypot/True0xAZ'
            ),
            array(
                'image' => 'podobaev.jpg',
                'person' => 'Подобаев Максим',
                'position' => 'Разработчик Task-based',
                'tg' => 'swissarmy',
                'team' => 'Life'
            ),
            array(
                'image' => 'dima.jpg',
                'person' => 'Муковкин Дмитрий',
                'position' => 'Разработчик сайта и flag rush',
                'tg' => 'mukovkin',
                'team' => 'Keva'
            ),
            array(
                'image' => 'tima.png',
                'person' => 'Абдибали Темирлан',
                'position' => 'Дизайнер',
                'tg' => 'temtemtem'
            ),
            array(
                'image' => 'pnastya.jpg',
                'person' => 'Популова Анастасия',
                'position' => 'Руководитель волонтёров',
                'tg' => 'AnastasiyaEf'
            ),
            array(
                'image' => 'volkov.jpg',
                'person' => 'Колодин Алексей',
                'position' => 'Системный администратор',
            ),
            array(
                'image' => 'biryukov.jpg',
                'person' => 'Бирюков Александр',
                'position' => 'Системный администратор',
            ),
            array(
                'image' => 'ivanov_andrey.jpg',
                'person' => 'Иванов Андрей',
                'position' => 'Системный администратор',
                'tg' => 'noob4ik'
            ),
            array(
                'person' => 'Харламов Алексей',
                'position' => 'Системный администратор',
                'tg' => 'derlafff'
            ),
            array(
                'person' => 'Лискин Кирилл',
                'position' => 'Системный администратор',
                'tg' => 'ga88er'
            ),
            array(
                'image' => 'blazevich.png',
                'person' => 'Блажевич Александр',
                'position' => 'Системный администратор',
            )
        );

        return $this->render('SibirCtfBundle::teams.html.twig', array(
            'teams' => $teams,
            'organizing' => $organizing
        ));
    }


    /**
     * @Route("/jury", name="jury")
     */
    public function juryAction()
    {
        $jury = array(
//            array(
//                'person' => 'Александр Будников',
//                'image' => 'budnikov.jpg',
//                'company' => 'Член-корреспондент Академии криптографии РФ',
//                'description' => 'Председатель жюри. Управляющий директор по информационной безопасности и специальным разработкам ПАО АФК «Система»'
//            ),
            array(
                'person' => 'Старостина Екатерина',
                'position' => 'консультант',
                'company' => 'FinCERT Банка России',
                'image' => 'starostina.jpg',
                'description' => 'Опыт работы в сфере кибербезопасности в части организационно-правовых вопросов — более 10 лет. На сегодняшний день — руководитель образовательной программы по ИБ в Московском Политехническом Университете, консультант FinCERT Банка России в вопросах повышения финансовой осведомленности безопасности платежных услуг. Обладатель премии Security Awards-2013 «Женщина в Информационной безопасности».'
            ),
            array(
                'person' => 'Дмитрий Скляров',
                'position' => 'Руководитель отдела исследований приложений',
                'company' => 'Positive Technologies',
                "image" => 'Sklyrov.jpg',
                'description' => 'Известный российский программист, разработчик алгоритма программы Advanced eBook Processor. Выступления Дмитрия можно услышать на всероссийских соревнованиях по защите информации RuCTF и Летней школе CTF в Дубне.'
            ),
            array(
                'person' => 'Михаил Кадер',
                'position' => 'Заслуженный системный инженер',
                'company' => 'Cisco Systems Ltd.',
                'image' => 'Kader.jpg',
                'description' => 'Ведущий консультант Cisco по вопросам информационной безопасности в России и других странах СНГ. Отвечает за техническую поддержку инженеров, партнеров и клиентов Cisco, а также занимается разработкой и реализацией стратегии Cisco в области информационной безопасности в регионе. Михаил стал первым сотрудником компании в России и других странах СНГ, кто удостоился почетного титула, — заслуженный системный инженер.'
            ),
            array(
                'person' => 'Владимир Елисеев',
                'company' => 'Инфотекс',
                'image' => 'Eliseev.jpg',
                'description' => 'Руководитель Центра научных исследований и перспективных разработок (ЦНИПР) ОАО «ИнфоТеКС»'
            ),
//            array(
//                'person' => 'Денис Гамаюнов',
//                'position' => 'И.о. зав. Лабораторией безопасности информационных систем МГУ',
//                'company' => 'ВМК МГУ',
//                'image' => 'gamaynov.jpg',
//                'description' => 'Кандидат физико-математических наук (2007), руководитель спецсеминаров и спецкурсов по информационной безопасности. Один из организаторов Moscow CTF School — соревнований по информационной безопасности формата Capture the flag для школьников столицы и Московской области'
//            ),
            array(
                'person' => 'Алексей Зайцев',
                'company' => 'АФК «Система»',
                'image' => 'zaycev.jpg',
                'description' => 'Директор по информационной безопасности ПАО АФК АФК «Система»'
            ),
            array(
                'person' => 'Илья Дерлыш',
                'company' => 'ООО «Кронштадт беспилотные системы»',
                'image' => 'derlysh.jpg',
                'description' => 'Начальник отдела по информационной безопасности и специальным проектам'
            ),
            array(
                'person' => 'Павел Супрунюк',
                'position' => 'Ведущий аналитик направления «Аудит и консалтинг»',
                'company' => 'Group-IB',
                'image' => 'suprunyuk.jpg',
                'description' => 'В сфере ИТ и ИБ — 10 лет, ранее работал специалистом по проектированию и внедрению информационных систем, средств защиты информации, системным архитектором. В настоящее время занимается проведением комплексных тестов на проникновение, оценкой защищенности веб-приложений. Имеет международный сертификат Offensive Security Certified Professional (OSCP) по тестированию на проникновение.'
            ),
            array(
                'person' => 'Андрей Масалович',
                'position' => 'Ведущий эксперт по конкурентной разведке',
                'company' => 'Лавина Пульс',
                'image' => 'masalovich.jpg',
                'description' => 'Разработчик поисково-аналитической системы Avalanche для борьбы с сетевыми угрозами. Президент Консорциума Инфорус, кандидат физико-математических наук, лауреат стипендии РАН «Выдающийся ученый России», премии IPCC «Лучшая работа в компьютерной журналистике России» и других. Автор более огромного количества печатных работ.'
            ),
            array(
                'person' => 'Илья Шабанов',
                'company' => 'Anti-Malware',
                'image' => 'shabanov.jpg',
                'position' => 'Генеральный директор'
            ),
            array(
                'person' => 'Волков Сергей',
                'company' => 'АО «ГОЗНАК»',
                'image' => 'volkov.png',
                'position' => 'Начальник отдела информационной безопасности'
            ),
            array(
                'person' => 'Игорь Черватюк',
                'company' => 'Официальный представитель КПМГ',
                'image' => 'chervatuk.jpg',
                'position' => 'Независимый аудитор соревнований',
                'description' => 'Cпециалист в области практической безопасности, реагирования на инциденты и тестирований на проникновение. Является научным руководителем исследовательского научно-образовательного центра КПМГ «Защита информации и кибербезопасность» на базе МГТУ им. Н.Э. Баумана, обладает международным сертификатом Offensive Security Certified Professional (OSCP) по тестированию на проникновение.'            ),
            array(
                'person' => 'Петр Девянин',
                'position' => 'Преподаватель',
                'company' => 'Член-корреспондент Академии криптографии РФ',
                'description' => 'Доктор технических наук, председатель УМС Учебно-методического объединения по информационной безопасности.'
            ),
        );

        return $this->render('SibirCtfBundle::jury.html.twig', array(
            'jury' => $jury
        ));
    }

    /**
     * @Route("/sponsors", name="sponsors")
     */
    public function sponsorsAction()
    {
        return $this->render('SibirCtfBundle::sponsors.html.twig');
    }

    /**
     * @Route("/news", name="news")
     */
    public function newsAction()
    {
        $repository = $this->getDoctrine()->getRepository('SibirCtfBundle:News');

        $news = $repository->findBy(array(), array('date' => 'DESC'));

        return $this->render('SibirCtfBundle::news.html.twig', array(
            'news' => $news
        ));
    }

    /**
     * @Route("/news/{news}", name="news-full")
     */
    public function newsFullAction(Request $request, News $news)
    {
        return $this->render('SibirCtfBundle::news-full.html.twig', array(
            'news' => $news,
            'months' => array(
                '1' => 'января',
                '2' => 'февраля',
                '3' => 'марта',
                '4' => 'апреля',
                '5' => 'мая',
                '6' => 'июня',
                '7' => 'июля',
                '8' => 'августа',
                '9' => 'сентября',
                '10' => 'октября',
                '11' => 'ноября',
                '12' => 'декабря',
            )
        ));
    }

    /**
     * @Route("/results", name="results")
     */
    public function resultsAction()
    {
        return $this->render('SibirCtfBundle::results.html.twig');
    }

    /**
     * @Route("/conference", name="conference")
     */
    public function conferenceAction()
    {
        return $this->render('SibirCtfBundle:17:conference.html.twig');
    }

    /**
     * @Route("/rules", name="rules")
     */
    public function rulesAction()
    {
        return $this->render('SibirCtfBundle::rules.html.twig');
    }

    /**
     * @Route("/place", name="place")
     */
    public function placeAction()
    {
        return $this->render('SibirCtfBundle::place.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('SibirCtfBundle::about.html.twig');
    }
}
