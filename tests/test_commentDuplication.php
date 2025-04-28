<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class CommentDuplicationTest extends TestCase
{
    /** @var Client */
    private $http;

    /** Basis-URL eurer Anwendung */
    private $baseUri = 'http://localhost/Social_App';

    protected function setUp(): void
    {
        // Guzzle-Client mit Cookie-Jar (für Session-Handling)
        $this->http = new Client([
            'base_uri' => $this->baseUri,
            'cookies'  => true,
            'http_errors' => false,
        ]);
    }

    /**
     * Login eines Users, gibt das CookieJar zurück
     *
     * @param string $user
     * @param string $pass
     * @return CookieJar
     */
    private function login(string $user, string $pass): CookieJar
    {
        $jar = new CookieJar;
        $res = $this->http->post('/controllers/login.php', [
            'form_params' => [
                'username' => $user,
                'password' => $pass,
            ],
            'cookies' => $jar,
        ]);
        $this->assertEquals(302, $res->getStatusCode(), "Login sollte per Redirect enden");
        return $jar;
    }

    /**
     * Fügt einen Kommentar via API hinzu und gibt den JSON-Antwort-Array zurück
     */
    private function postComment(CookieJar $jar, int $postId, string $content): array
    {
        $res = $this->http->post('/controllers/create_comment.php', [
            'cookies' => $jar,
            'form_params' => [
                'post_id' => $postId,
                'comment' => $content,
            ]
        ]);
        $this->assertEquals(200, $res->getStatusCode(), 'create_comment.php muss HTTP 200 zurückliefern');
        $json = json_decode((string)$res->getBody(), true);
        $this->assertTrue($json['success'], 'API gab success =false zurück: ' . ($json['message'] ?? ''));
        return $json;
    }

    /**
     * Ruft die Feed-Seite ab und gibt das HTML zurück
     */
    private function fetchFeed(CookieJar $jar): string
    {
        $res = $this->http->get('/feed.view.php', [
            'cookies' => $jar,
        ]);
        $this->assertEquals(200, $res->getStatusCode());
        return (string)$res->getBody();
    }

    public function testCommentAppearsOnlyOnceAfterReloadAndPolling()
    {
        // 1) Login als User B und poste Kommentar auf Post 138
        $jarB = $this->login('userB', 'passwordB');
        $json = $this->postComment($jarB, 138, 'automated-test-comment');

        // aus API-Antwort erhalten wir $json['comment']['id']
        $cid = $json['comment']['id'] ?? null;
        $this->assertNotNull($cid);

        // 2) Login als User A und rufe Feed ab
        $jarA = $this->login('userA', 'passwordA');
        $html1 = $this->fetchFeed($jarA);

        // Kommentar-Snippet sollte exakt **einmal** vorkommen
        $occ1 = substr_count($html1, 'automated-test-comment');
        $this->assertEquals(1, $occ1, "Kommentar muss genau einmal im HTML stehen (Load #1)");

        // 3) Simuliere Live-Poll: Kommentare-API abfragen und Render-Methode ausführen
        $resApi = $this->http->get(
            "/controllers/api/comments_since.php",
            ['query' => ['since' => '1970-01-01 00:00:00'], 'cookies' => $jarA]
        );
        $this->assertEquals(200, $resApi->getStatusCode());
        $apiData = json_decode((string)$resApi->getBody(), true);
        $this->assertTrue($apiData['success']);

        // 4) Nochmalige Feed-Abfrage per Reload
        $html2 = $this->fetchFeed($jarA);
        $occ2 = substr_count($html2, 'automated-test-comment');
        $this->assertEquals(1, $occ2, "Kommentar muss auch nach Reload genau einmal im HTML stehen (Load #2)");
    }
}
