'use strict';
document.addEventListener('DOMContentLoaded', function () {
    const hero = document.getElementById('hive-hero'); if (!hero) return;
    const video = document.getElementById('hive-video'); const videoEl = document.getElementById('hive-video-el');
    const skipBtn = document.getElementById('hive-skip'); const dontShow = document.getElementById('hive-dontshow');
    const insideScene = document.getElementById('hive-inside');
    const STORAGE_KEY = 'hive_intro_disabled'; let finished = false;

    function showHoneycomb() {
        if (finished) return; finished = true;
        if (dontShow && dontShow.checked) localStorage.setItem(STORAGE_KEY, '1'); else localStorage.removeItem(STORAGE_KEY);
        if (video) { video.classList.add('is-hidden'); setTimeout(() => { video.style.display = 'none'; }, 800); }
        if (insideScene) insideScene.classList.add('is-active');
        if (videoEl) { try { videoEl.pause(); } catch (e) {} }
    }

    if (localStorage.getItem(STORAGE_KEY) === '1') {
        if (video) video.style.display = 'none'; if (insideScene) insideScene.classList.add('is-active'); finished = true;
    } else {
        if (dontShow) dontShow.checked = true;
        if (skipBtn) skipBtn.addEventListener('click', showHoneycomb);
        if (video) video.addEventListener('click', showHoneycomb);
        if (videoEl) {
            videoEl.addEventListener('ended', showHoneycomb); videoEl.addEventListener('error', showHoneycomb);
            videoEl.addEventListener('loadedmetadata', () => setTimeout(showHoneycomb, (videoEl.duration || 5) * 1000 + 1500));
            setTimeout(showHoneycomb, 8000); // Абсолютная страховка
        }
    }
});
