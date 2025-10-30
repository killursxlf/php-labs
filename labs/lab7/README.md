## Screenshots

### 1) Client cache — CSS/JS

**First request — 200 OK with cache headers** (`Cache-Control`, `ETag`, `Last-Modified`, `Expires`).

![Client cache 200 OK](report/01.png)

**Second request — served from cache.**

![Client cache cached/304](report/02.png)

---

### 2) File cache — `generate-report.php`

**First load — `X-Cache: MISS` (~3s), cache file created.**

![File cache MISS](report/03-file-cache.png)

**Second load — `X-Cache: HIT` (fast), served from cache.**

![File cache HIT](report/04-file-cache.png)

---

### 3) Session cache — `session-cache.php`

**First visit — Status: MISS, about 2000 ms.**

![Session cache MISS](report/05-session-cache.png)

**Second visit — Status: HIT, fast (same session).**

![Session cache HIT](report/06-session-cache.png)

---

### 4) Static memoization — `static-cache.php`

**Within one HTTP request:** first call ~2000 ms, second call ~0 ms (cache only during render).

![Static memoization](report/07-static-cache.png)

---

## Explanation

* **styles.php / app.php** return CSS/JS with `Cache-Control: public, max-age=86400`, `Expires +1 day`, `ETag`, `Last-Modified` and support 304.
* **generate-report.php** renders a heavy table and caches HTML to `cache/report.html` for 10 minutes; sets `X-Cache: HIT/MISS`.
* **session-cache.php** stores calculated data in `$_SESSION` for the current user/session.
* **static-cache.php** uses a `static` property to avoid repeated work inside the same request.
