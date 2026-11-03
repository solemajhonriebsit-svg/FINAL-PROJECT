(function () {
    'use strict';

    var API_BASE = 'https://jsonplaceholder.typicode.com';

    function $(id) { return document.getElementById(id); }

    function esc(value) {
        var div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }

    function loading(el, text) {
        el.innerHTML = '<span class="loading"><span class="spinner"></span>' + esc(text || 'Loading...') + '</span>';
    }

    function msg(type, html) {
        return '<div class="msg ' + type + '" style="margin-top:0">' + html + '</div>';
    }

    function showJson(el, data) {
        el.innerHTML = '<pre>' + esc(JSON.stringify(data, null, 2)) + '</pre>';
    }

    function setBusy(btn, busy) { if (btn) { btn.disabled = busy; } }

    function postCard(post) {
        return '<h4>' + esc(post.title) + '</h4><p style="margin:0">' + esc(post.body).replace(/\n/g, '<br>') + '</p>';
    }

    var nav = $('mainNav');
    var toggle = $('navToggle');

    toggle.addEventListener('click', function () {
        var open = nav.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    nav.addEventListener('click', function (e) {
        if (e.target.tagName === 'A') {
            nav.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    var navLinks = Array.prototype.slice.call(nav.querySelectorAll('a'));
    var sections = navLinks.map(function (a) { return document.querySelector(a.getAttribute('href')); });

    function updateActive() {
        var pos = window.scrollY + 120;
        var current = 0;
        sections.forEach(function (s, i) { if (s && s.offsetTop <= pos) { current = i; } });
        navLinks.forEach(function (a, i) { a.classList.toggle('active', i === current); });
    }
    window.addEventListener('scroll', updateActive, { passive: true });
    updateActive();

    if (window.LAB_ACTIVE_SECTION) {
        var target = $(window.LAB_ACTIVE_SECTION);
        if (target) { target.scrollIntoView(); }
    }

    function tick() {
        var now = new Date();
        var time = now.toLocaleTimeString('en-US', { hour12: true });
        var date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.querySelectorAll('[data-clock]').forEach(function (el) { el.textContent = time; });
        document.querySelectorAll('[data-date]').forEach(function (el) { el.textContent = date; });
    }
    tick();
    setInterval(tick, 1000);

    var greetingForm = $('greetingForm');
    if (greetingForm) {
        greetingForm.addEventListener('submit', function (e) {
            var input = $('greeting_name');
            var err = $('greetingClientError');
            if (input.value.trim() === '') {
                e.preventDefault();
                err.textContent = 'Please enter your name before submitting.';
                err.hidden = false;
                input.focus();
            } else {
                err.hidden = true;
            }
        });
    }

    var btnJsonApi = $('btnJsonApi');
    btnJsonApi.addEventListener('click', function () {
        var out = $('jsonApiOutput');
        var id = $('apiUserId').value;
        setBusy(btnJsonApi, true);
        loading(out, 'Requesting actions/json-api.php?id=' + id + ' ...');

        fetch('actions/json-api.php?id=' + encodeURIComponent(id), { headers: { 'Accept': 'application/json' } })
            .then(function (res) {
                var type = res.headers.get('Content-Type') || '';
                return res.json().then(function (data) { return { res: res, data: data, type: type }; });
            })
            .then(function (r) {
                var cls = r.res.ok ? 'success' : 'error';
                out.innerHTML = msg(cls, 'HTTP ' + r.res.status + ' &middot; Content-Type: <code>' + esc(r.type) + '</code>') +
                    '<div style="margin-top:.75rem"></div><pre>' + esc(JSON.stringify(r.data, null, 2)) + '</pre>';
            })
            .catch(function (err) {
                out.innerHTML = msg('error', 'Could not reach the endpoint. Make sure Apache is running and you opened the site via http://localhost/it-laboratory/. (' + esc(err.message) + ')');
            })
            .finally(function () { setBusy(btnJsonApi, false); });
    });

    function sendJsonBody(body, out, buttons) {
        buttons.forEach(function (b) { setBusy(b, true); });
        loading(out, 'Sending to actions/json-input.php ...');

        fetch('actions/json-input.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: body
        })
            .then(function (res) { return res.json().then(function (data) { return { res: res, data: data }; }); })
            .then(function (r) {
                if (r.res.ok && r.data.success) {
                    var v = r.data.received;
                    out.innerHTML = msg('success', esc(r.data.message)) +
                        '<div class="table-wrap" style="margin-top:.75rem"><table><tbody>' +
                        '<tr><th>Name</th><td>' + esc(v.name) + '</td></tr>' +
                        '<tr><th>Age</th><td>' + esc(v.age) + '</td></tr>' +
                        '<tr><th>Email</th><td>' + esc(v.email) + '</td></tr>' +
                        '</tbody></table></div>' +
                        '<p class="small muted" style="margin:.75rem 0 0">Bytes read from php://input: ' + esc(r.data.bytes_read) + '</p>';
                } else {
                    var detail = (r.data.details || []).map(function (d) { return '<li>' + esc(d) + '</li>'; }).join('');
                    out.innerHTML = msg('error', '<strong>HTTP ' + r.res.status + ':</strong> ' + esc(r.data.error || 'Request failed.') +
                        (detail ? '<ul>' + detail + '</ul>' : ''));
                }
            })
            .catch(function (err) {
                out.innerHTML = msg('error', 'Could not reach the endpoint. (' + esc(err.message) + ')');
            })
            .finally(function () { buttons.forEach(function (b) { setBusy(b, false); }); });
    }

    var jsonInputForm = $('jsonInputForm');
    var btnInvalid = $('btnInvalidJson');
    jsonInputForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var ageRaw = $('ji_age').value.trim();
        var payload = {
            name: $('ji_name').value.trim(),
            age: ageRaw === '' ? null : Number(ageRaw),
            email: $('ji_email').value.trim()
        };
        sendJsonBody(JSON.stringify(payload), $('jsonInputOutput'), [jsonInputForm.querySelector('button[type=submit]'), btnInvalid]);
    });
    btnInvalid.addEventListener('click', function () {
        sendJsonBody('{name: "Broken", age: 20', $('jsonInputOutput'), [jsonInputForm.querySelector('button[type=submit]'), btnInvalid]);
    });

    var ajaxForm = $('ajaxForm');
    ajaxForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var out = $('ajaxOutput');
        var btn = $('btnAjax');
        setBusy(btn, true);
        loading(out, 'Sending form data to PHP ...');

        fetch('actions/ajax-handler.php', {
            method: 'POST',
            body: new FormData(ajaxForm),
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (res) { return res.json().then(function (data) { return { res: res, data: data }; }); })
            .then(function (r) {
                if (r.res.ok && r.data.success) {
                    out.innerHTML = msg('success', '&#10003; ' + esc(r.data.message)) +
                        '<div style="margin-top:.75rem"></div><pre>' + esc(JSON.stringify(r.data, null, 2)) + '</pre>';
                } else {
                    var list = (r.data.errors || ['Something went wrong.']).map(function (x) { return '<li>' + esc(x) + '</li>'; }).join('');
                    out.innerHTML = msg('error', '<ul>' + list + '</ul>');
                }
            })
            .catch(function (err) {
                out.innerHTML = msg('error', 'Request failed: ' + esc(err.message));
            })
            .finally(function () { setBusy(btn, false); });
    });

    var btnXhr = $('btnXhr');
    btnXhr.addEventListener('click', function () {
        var output = $('output');
        setBusy(btnXhr, true);
        loading(output, 'Loading post data with XMLHttpRequest ...');

        var xhr = new XMLHttpRequest();
        xhr.open('GET', API_BASE + '/posts/1', true);
        xhr.timeout = 10000;

        xhr.onload = function () {
            setBusy(btnXhr, false);
            if (xhr.status === 200) {
                try {
                    var post = JSON.parse(xhr.responseText);
                    output.innerHTML = postCard(post);
                } catch (err) {
                    output.innerHTML = msg('error', 'The response could not be parsed as JSON.');
                }
            } else {
                output.innerHTML = msg('error', 'Request failed with HTTP status ' + esc(xhr.status) + '.');
            }
        };
        xhr.onerror = function () {
            setBusy(btnXhr, false);
            output.innerHTML = msg('error', 'Network error: the request could not be completed. Check your internet connection.');
        };
        xhr.ontimeout = function () {
            setBusy(btnXhr, false);
            output.innerHTML = msg('error', 'The request timed out after 10 seconds.');
        };
        xhr.send();
    });

    var btnFetchGet = $('btnFetchGet');
    btnFetchGet.addEventListener('click', function () {
        var out = $('fetchGetOutput');
        setBusy(btnFetchGet, true);
        loading(out, 'Fetching post ...');

        fetch(API_BASE + '/posts/1')
            .then(function (response) {
                if (!response.ok) { throw new Error('HTTP error: status ' + response.status); }
                return response.json();
            })
            .then(function (post) { out.innerHTML = postCard(post); })
            .catch(function (err) {
                var network = err instanceof TypeError;
                out.innerHTML = msg('error', network
                    ? 'Network error: could not reach JSONPlaceholder. Check your internet connection.'
                    : esc(err.message));
            })
            .finally(function () { setBusy(btnFetchGet, false); });
    });

    var btnFetchPost = $('btnFetchPost');
    btnFetchPost.addEventListener('click', function () {
        var out = $('fetchPostOutput');
        setBusy(btnFetchPost, true);
        loading(out, 'Sending POST request ...');

        var newPost = { title: 'My New Post', body: 'Learning Fetch API is fun!', userId: 1 };

        fetch(API_BASE + '/posts', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newPost)
        })
            .then(function (response) {
                if (!response.ok) { throw new Error('HTTP error: status ' + response.status); }
                return response.json();
            })
            .then(function (data) {
                out.innerHTML = msg('success', 'Response received (simulated creation):') +
                    '<div style="margin-top:.75rem"></div><pre>' + esc(JSON.stringify(data, null, 2)) + '</pre>';
            })
            .catch(function (err) {
                var network = err instanceof TypeError;
                out.innerHTML = msg('error', network
                    ? 'Network error: could not reach JSONPlaceholder. Check your internet connection.'
                    : esc(err.message));
            })
            .finally(function () { setBusy(btnFetchPost, false); });
    });

    function renderStudents(students, source) {
        var rows = students.map(function (s) {
            return '<tr><td class="mono">' + esc(s.id) + '</td><td>' + esc(s.name) + '</td><td>' + esc(s.course) + '</td><td>' + esc(s.year) + '</td></tr>';
        }).join('');
        return msg('success', 'Loaded ' + students.length + ' student record(s) ' + esc(source) + '.') +
            '<div class="table-wrap" style="margin-top:.75rem"><table><thead><tr><th>ID</th><th>Name</th><th>Course</th><th>Year</th></tr></thead><tbody>' +
            rows + '</tbody></table></div>';
    }

    var btnXmlJs = $('btnXmlJs');
    btnXmlJs.addEventListener('click', function () {
        var out = $('xmlOutput');
        setBusy(btnXmlJs, true);
        loading(out, 'Fetching student_data.xml ...');

        fetch('student_data.xml', { cache: 'no-store' })
            .then(function (res) {
                if (!res.ok) { throw new Error('student_data.xml could not be loaded (HTTP ' + res.status + ').'); }
                return res.text();
            })
            .then(function (text) {
                var doc = new DOMParser().parseFromString(text, 'application/xml');
                if (doc.getElementsByTagName('parsererror').length) {
                    throw new Error('The XML file could not be parsed. Check that it is well-formed.');
                }
                var nodes = doc.getElementsByTagName('student');
                var students = Array.prototype.map.call(nodes, function (node) {
                    var get = function (tag) {
                        var el = node.getElementsByTagName(tag)[0];
                        return el ? el.textContent.trim() : '';   // textContent keeps "001" as text
                    };
                    return { id: get('id'), name: get('name'), course: get('course'), year: get('year') };
                });
                if (!students.length) { throw new Error('No <student> records were found in the XML file.'); }
                out.innerHTML = renderStudents(students, 'with Fetch + DOMParser');
            })
            .catch(function (err) {
                var text = err instanceof TypeError
                    ? 'Could not load student_data.xml. Open the site through http://localhost/it-laboratory/ (not by double-clicking the file).'
                    : err.message;
                out.innerHTML = msg('error', esc(text));
            })
            .finally(function () { setBusy(btnXmlJs, false); });
    });

    var btnXmlPhp = $('btnXmlPhp');
    btnXmlPhp.addEventListener('click', function () {
        var out = $('xmlOutput');
        setBusy(btnXmlPhp, true);
        loading(out, 'Asking PHP to read student_data.xml ...');

        fetch('actions/xml-reader.php', { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json().then(function (data) { return { res: res, data: data }; }); })
            .then(function (r) {
                if (!r.res.ok || !r.data.success) { throw new Error(r.data.error || 'PHP could not read the XML file.'); }
                out.innerHTML = renderStudents(r.data.students, 'with PHP SimpleXML');
            })
            .catch(function (err) { out.innerHTML = msg('error', esc(err.message)); })
            .finally(function () { setBusy(btnXmlPhp, false); });
    });
})();
