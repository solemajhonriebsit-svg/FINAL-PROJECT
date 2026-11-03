<?php
define('LAB_APP', true);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/actions/greeting.php';
require_once __DIR__ . '/actions/get-demo.php';
require_once __DIR__ . '/actions/post-demo.php';

$greeting = handle_greeting();
$getDemo  = handle_get_demo();
$postDemo = handle_post_demo();

$studentArray = ['name' => 'Jhonrie Solema', 'age' => 22, 'course' => 'Information Technology'];
$encodedJson  = json_encode($studentArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$sampleJson   = '{"name":"Ana Reyes","age":21,"email":"ana.reyes@example.com"}';
$decodedObj   = json_decode($sampleJson);        // stdClass object
$decodedArray = json_decode($sampleJson, true);  // associative array

$activeSection = $greeting['submitted'] ? 'greeting' : ($getDemo['submitted'] ? 'php-get' : ($postDemo['submitted'] ? 'php-post' : ''));

require __DIR__ . '/includes/header.php';
?>

<section id="home" class="hero">
    <div class="container">
        <div class="grid grid-2" style="align-items:center">
            <div>
                <span class="eyebrow">Integrated IT Laboratory</span>
                <h1>Eight exercises. <span>One working website.</span></h1>
                <p class="lead">Try every laboratory activity live: PHP forms, GET and POST, JSON encoding and decoding, AJAX, XMLHttpRequest, the Fetch API and XML parsing &mdash; all in one place.</p>
                <div class="hero-actions">
                    <a class="btn mint" href="#php">Start with PHP</a>
                    <a class="btn ghost" href="#api">Test the APIs</a>
                </div>
            </div>
            <div class="card clock-card">
                <p class="muted small" style="margin-bottom:.2rem">Live clock (JavaScript)</p>
                <div class="clock" data-clock>--:--:--</div>
                <p class="muted small" style="margin:0" data-date></p>
            </div>
        </div>

        <div class="dash-grid" aria-label="Exercise dashboard">
            <a class="dash-item" href="#profile"><strong>Exercise 1</strong>Personal profile page<br><span>HTML &amp; CSS</span></a>
            <a class="dash-item" href="#greeting"><strong>Exercise 2</strong>Greeting app<br><span>PHP + JS clock</span></a>
            <a class="dash-item" href="#php-get"><strong>Exercise 3</strong>GET method<br><span>$_GET form</span></a>
            <a class="dash-item" href="#php-post"><strong>Exercise 4</strong>POST method<br><span>$_POST submission</span></a>
            <a class="dash-item" href="#json"><strong>Exercise 5</strong>PHP &amp; JSON labs<br><span>5 working labs</span></a>
            <a class="dash-item" href="#ajax"><strong>Exercise 6</strong>XMLHttpRequest<br><span>Needs internet</span></a>
            <a class="dash-item" href="#api"><strong>Exercise 7</strong>Fetch API<br><span>GET + POST, needs internet</span></a>
            <a class="dash-item" href="#xml"><strong>Exercise 8</strong>XML document<br><span>student_data.xml</span></a>
        </div>
    </div>
</section>

<section id="profile" class="block">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Exercise 1</span>
            <h2>Personal Profile</h2>
        </div>
        <div class="card profile">
            <?php
            $profileImg = '';
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                if (is_file(__DIR__ . "/assets/profile.$ext")) { $profileImg = "assets/profile.$ext"; break; }
            }
            ?>
            <?php if ($profileImg): ?>
                <img class="avatar avatar-photo" src="<?= e($profileImg) ?>?v=<?= filemtime(__DIR__ . '/' . $profileImg) ?>" alt="Photo of Jhonrie Solema" width="220" height="220">
            <?php else: ?>
                <div class="avatar" role="img" aria-label="Profile placeholder with initials JS">JS</div>
            <?php endif; ?>
            <div>
                <h3 style="font-size:1.7rem">Jhonrie Solema</h3>
                <div class="meta">
                    <span class="chip">🎓 Information Technology</span>
                    <span class="chip">📍 Muntinlupa City</span>
                </div>
                <p>I am an Information Technology student who enjoys turning ideas into working web applications. This laboratory website brings my exercises together in one place, from simple PHP forms to JSON, AJAX, the Fetch API and XML processing.</p>
                <h4 style="margin-top:1rem">Skills</h4>
                <div class="skills">
                    <span class="skill">HTML</span><span class="skill">CSS</span><span class="skill">JavaScript</span>
                    <span class="skill">PHP</span><span class="skill">JSON</span><span class="skill">AJAX</span>
                    <span class="skill">Fetch API</span><span class="skill">XML</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ EXERCISES 2-4: PHP ACTIVITIES ============ -->
<section id="php" class="block">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Exercises 2 &ndash; 4</span>
            <h2>PHP Activities</h2>
            <p class="muted">Server-side forms handled with PHP. Each form sends you back to this same page.</p>
        </div>
        <div class="sub-nav">
            <a href="#greeting">Greeting</a><a href="#php-get">GET method</a><a href="#php-post">POST method</a>
        </div>

        <div class="grid grid-2">
            <!-- Exercise 2 -->
            <article class="card" id="greeting">
                <span class="badge mint">Exercise 2</span>
                <h3>PHP Greeting + Live Clock</h3>
                <p class="muted">Enter your name, PHP builds a personalised greeting. The clock is updated by JavaScript.</p>
                <p class="clock" style="font-size:1.6rem" data-clock>--:--:--</p>
                <form method="POST" action="index.php#greeting" id="greetingForm" novalidate>
                    <input type="hidden" name="form" value="greeting">
                    <div class="field">
                        <label for="greeting_name">Your name</label>
                        <input type="text" id="greeting_name" name="greeting_name" maxlength="60"
                               placeholder="e.g. Jhonrie Solema" value="<?= e($greeting['name']) ?>">
                    </div>
                    <button class="btn" type="submit">Greet me</button>
                </form>
                <div id="greetingClientError" class="msg error" hidden></div>
                <?php if ($greeting['submitted']): ?>
                    <?php if ($greeting['errors']): ?>
                        <div class="msg error"><ul><?php foreach ($greeting['errors'] as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
                    <?php else: ?>
                        <div class="msg success"><?= e($greeting['greeting']) ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </article>

            <!-- Exercise 3 -->
            <article class="card" id="php-get">
                <span class="badge mint">Exercise 3</span>
                <h3>PHP GET Method</h3>
                <p class="muted">The form uses <code class="inline">method="GET"</code>, so the values appear in the URL and are read with <code class="inline">$_GET</code>.</p>
                <form method="GET" action="index.php#php-get" novalidate>
                    <input type="hidden" name="lab" value="get">
                    <div class="field">
                        <label for="get_student">Student name</label>
                        <input type="text" id="get_student" name="student_name" maxlength="60"
                               placeholder="e.g. Maria Santos" value="<?= e($getDemo['student']) ?>">
                    </div>
                    <div class="field">
                        <label for="get_course">Course</label>
                        <input type="text" id="get_course" name="course" maxlength="80"
                               placeholder="e.g. BSIT" value="<?= e($getDemo['course']) ?>">
                    </div>
                    <button class="btn" type="submit">Submit with GET</button>
                </form>
                <?php if ($getDemo['submitted']): ?>
                    <?php if ($getDemo['errors']): ?>
                        <div class="msg error"><ul><?php foreach ($getDemo['errors'] as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
                    <?php else: ?>
                        <div class="msg success">Values received through <code>$_GET</code>:
                            <ul>
                                <li><strong>Student:</strong> <?= e($getDemo['student']) ?></li>
                                <li><strong>Course:</strong> <?= e($getDemo['course']) ?></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <p class="small muted" style="margin-top:.75rem">Query string in the address bar:</p>
                    <div class="url-box"><?= e('?' . ($_SERVER['QUERY_STRING'] ?? '')) ?></div>
                <?php endif; ?>
            </article>
        </div>

        <!-- Exercise 4 -->
        <article class="card" id="php-post" style="margin-top:1.25rem">
            <span class="badge mint">Exercise 4</span>
            <h3>PHP POST Method &mdash; Activity Submission</h3>
            <p class="muted">Data is sent in the request body with <code class="inline">method="POST"</code> (not visible in the URL) and read with <code class="inline">$_POST</code>.</p>
            <div class="grid grid-2">
                <form method="POST" action="index.php#php-post" novalidate>
                    <input type="hidden" name="form" value="post">
                    <div class="field">
                        <label for="post_student">Student name</label>
                        <input type="text" id="post_student" name="student_name" maxlength="60" value="<?= e($postDemo['student']) ?>" placeholder="Your full name">
                    </div>
                    <div class="field">
                        <label for="post_title">Activity title</label>
                        <input type="text" id="post_title" name="activity_title" maxlength="100" value="<?= e($postDemo['title']) ?>" placeholder="e.g. Laboratory Exercise 4">
                    </div>
                    <div class="field">
                        <label for="post_desc">Activity description</label>
                        <textarea id="post_desc" name="activity_description" maxlength="1000" placeholder="Describe what you did..."><?= e($postDemo['description']) ?></textarea>
                    </div>
                    <button class="btn" type="submit">Submit activity</button>
                </form>
                <div>
                    <?php if ($postDemo['submitted']): ?>
                        <?php if ($postDemo['errors']): ?>
                            <div class="msg error"><ul><?php foreach ($postDemo['errors'] as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
                        <?php else: ?>
                            <div class="msg success">&#10003; Submission received on <?= e($postDemo['received_at']) ?>.</div>
                            <div class="output" style="border-style:solid">
                                <p><strong>Student:</strong> <?= e($postDemo['student']) ?></p>
                                <p><strong>Activity:</strong> <?= e($postDemo['title']) ?></p>
                                <p style="margin:0"><strong>Description:</strong><br><?= nl2br(e($postDemo['description'])) ?></p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="output"></div>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    </div>
</section>

<!-- ============ EXERCISE 5: JSON LABS ============ -->
<section id="json" class="block">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Exercise 5</span>
            <h2>PHP &amp; JSON Laboratory</h2>
            <p class="muted">Five working labs covering encoding, decoding, a JSON API, JSON input and AJAX.</p>
        </div>
        <div class="sub-nav">
            <a href="#json1">Lab 1 Encode</a><a href="#json2">Lab 2 Decode</a><a href="#json3">Lab 3 API</a>
            <a href="#json4">Lab 4 Input</a><a href="#json5">Lab 5 AJAX</a>
        </div>

        <div class="grid grid-2">
            <!-- Lab 1 -->
            <article class="card" id="json1">
                <div class="lab-title"><span class="lab-num">1</span><h3>JSON Encoding</h3></div>
                <p class="muted">A PHP associative array converted with <code class="inline">json_encode()</code>.</p>
                <p class="small"><strong>PHP array</strong></p>
                <div class="output" style="margin-top:.3rem"><pre><?= e(var_export($studentArray, true)) ?></pre></div>
                <p class="small" style="margin-top:1rem"><strong>JSON output</strong></p>
                <div class="output" style="margin-top:.3rem"><pre><?= e($encodedJson) ?></pre></div>
            </article>

            <!-- Lab 2 -->
            <article class="card" id="json2">
                <div class="lab-title"><span class="lab-num">2</span><h3>JSON Decoding</h3></div>
                <p class="muted">A JSON string decoded with <code class="inline">json_decode()</code> into an object and into an associative array.</p>
                <p class="small"><strong>Sample JSON string</strong></p>
                <div class="output" style="margin-top:.3rem"><pre><?= e($sampleJson) ?></pre></div>
                <div class="table-wrap" style="margin-top:1rem">
                    <table>
                        <thead><tr><th>Decoded as</th><th>Name</th><th>Email</th></tr></thead>
                        <tbody>
                            <tr><td>Object <code class="inline">-&gt;</code></td><td><?= e($decodedObj->name) ?></td><td><?= e($decodedObj->email) ?></td></tr>
                            <tr><td>Array <code class="inline">[]</code></td><td><?= e($decodedArray['name']) ?></td><td><?= e($decodedArray['email']) ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </article>

            <!-- Lab 3 -->
            <article class="card" id="json3">
                <div class="lab-title"><span class="lab-num">3</span><h3>JSON API Simulation</h3></div>
                <p class="muted">Endpoint <code class="inline">actions/json-api.php</code> returns a user profile with <code class="inline">Content-Type: application/json</code>.</p>
                <div class="field">
                    <label for="apiUserId">Choose a user ID</label>
                    <select id="apiUserId">
                        <option value="1">1 &ndash; Jhonrie Solema</option>
                        <option value="2">2 &ndash; Maria Santos</option>
                        <option value="3">3 &ndash; Jose Cruz</option>
                        <option value="99">99 &ndash; (does not exist, tests errors)</option>
                    </select>
                </div>
                <button class="btn" id="btnJsonApi" type="button">Request user profile</button>
                <div class="output" id="jsonApiOutput" aria-live="polite"></div>
            </article>

            <!-- Lab 4 -->
            <article class="card" id="json4">
                <div class="lab-title"><span class="lab-num">4</span><h3>JSON Input Processing</h3></div>
                <p class="muted">Sends JSON to <code class="inline">actions/json-input.php</code>, which reads <code class="inline">php://input</code> and decodes it.</p>
                <form id="jsonInputForm" novalidate>
                    <div class="field"><label for="ji_name">Name</label><input type="text" id="ji_name" placeholder="e.g. Jose Cruz"></div>
                    <div class="field"><label for="ji_age">Age</label><input type="number" id="ji_age" min="1" max="120" placeholder="e.g. 20"></div>
                    <div class="field"><label for="ji_email">Email</label><input type="email" id="ji_email" placeholder="e.g. jose@example.com"></div>
                    <div class="btn-row">
                        <button class="btn" type="submit">Send as JSON</button>
                        <button class="btn ghost" type="button" id="btnInvalidJson">Send invalid JSON</button>
                    </div>
                </form>
                <div class="output" id="jsonInputOutput" aria-live="polite"></div>
            </article>
        </div>

        <!-- Lab 5 -->
        <article class="card" id="json5" style="margin-top:1.25rem">
            <div class="lab-title"><span class="lab-num">5</span><h3>PHP, JSON, JavaScript &amp; AJAX</h3></div>
            <p class="muted">The form is sent with the Fetch API to <code class="inline">actions/ajax-handler.php</code>. PHP validates it and returns JSON, which is shown below without reloading the page.</p>
            <div class="grid grid-2">
                <form id="ajaxForm" novalidate>
                    <div class="field"><label for="aj_name">Name</label><input type="text" id="aj_name" name="name" maxlength="60" placeholder="Your name"></div>
                    <div class="field"><label for="aj_email">Email</label><input type="email" id="aj_email" name="email" placeholder="you@example.com"></div>
                    <div class="field"><label for="aj_course">Course</label><input type="text" id="aj_course" name="course" maxlength="80" placeholder="e.g. BSIT"></div>
                    <button class="btn" type="submit" id="btnAjax">Submit via AJAX</button>
                </form>
                <div class="output" id="ajaxOutput" aria-live="polite" style="margin-top:0"></div>
            </div>
        </article>
    </div>
</section>

<!-- ============ EXERCISE 6: XMLHttpRequest ============ -->
<section id="ajax" class="block">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Exercise 6</span>
            <h2>AJAX with XMLHttpRequest</h2>
        </div>
        <article class="card">
            <span class="badge">Requires internet</span>
            <h3>Load a post with XMLHttpRequest</h3>
            <p class="muted">Sends <code class="inline">GET https://jsonplaceholder.typicode.com/posts/1</code>. When the status is 200 the post title and body are displayed.</p>
            <button class="btn" type="button" id="btnXhr">Load Post Data</button>
            <div class="output" id="output" aria-live="polite"></div>
        </article>
    </div>
</section>
<!-- ============ EXERCISE 7: FETCH API ============ -->
<section id="api" class="block">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Exercise 7</span>
            <h2>API Testing &mdash; Fetch API</h2>
            <p class="muted">Both demonstrations call JSONPlaceholder, so an internet connection is required.</p>
        </div>
        <div class="grid grid-2">
            <article class="card">
                <span class="badge">Task 1 &middot; GET</span>
                <h3>Fetch GET Request</h3>
                <p class="muted">Fetches <code class="inline">/posts/1</code>, checks <code class="inline">response.ok</code>, then parses with <code class="inline">.json()</code>.</p>
                <button class="btn" type="button" id="btnFetchGet">Load Post Data</button>
                <div class="output" id="fetchGetOutput" aria-live="polite"></div>
            </article>

            <article class="card">
                <span class="badge">Task 2 &middot; POST</span>
                <h3>Fetch POST Request</h3>
                <p class="muted">Sends this JSON body to <code class="inline">/posts</code> with <code class="inline">Content-Type: application/json</code>:</p>
                <div class="output" style="margin-top:0;min-height:0"><pre>{
  "title": "My New Post",
  "body": "Learning Fetch API is fun!",
  "userId": 1
}</pre></div>
                <p style="margin-top:1rem"><button class="btn mint" type="button" id="btnFetchPost">Send POST Request</button></p>
                <div class="output" id="fetchPostOutput" aria-live="polite"></div>
                <div class="msg info">&#9432; JSONPlaceholder only <strong>simulates</strong> creating a post. The response looks real (it includes a new <code>id</code>), but nothing is permanently saved on the server.</div>
            </article>
        </div>
    </div>
</section>

<!-- ============ EXERCISE 8: XML ============ -->
<section id="xml" class="block">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Exercise 8</span>
            <h2>XML Document</h2>
        </div>
        <article class="card">
            <span class="badge mint">Works offline</span>
            <h3>Student records from student_data.xml</h3>
            <p class="muted">Load the XML file with the Fetch API (parsed in the browser with <code class="inline">DOMParser</code>) or let PHP read it with SimpleXML. Leading zeros in IDs are preserved.</p>
            <div class="btn-row">
                <button class="btn mint" type="button" id="btnXmlJs">Load with JavaScript</button>
                <button class="btn ghost" type="button" id="btnXmlPhp">Load with PHP</button>
                <a class="btn ghost" href="student_data.xml" target="_blank" rel="noopener">View raw XML</a>
            </div>
            <div class="output" id="xmlOutput" aria-live="polite"></div>
        </article>
    </div>
</section>

<?php if ($activeSection): ?>
<script>window.LAB_ACTIVE_SECTION = <?= json_encode($activeSection) ?>;</script>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
