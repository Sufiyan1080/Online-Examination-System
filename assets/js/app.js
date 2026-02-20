(() => {
  const timerEl = document.querySelector('#timer');
  if (timerEl) {
    let seconds = parseInt(timerEl.dataset.minutes || '0', 10) * 60;
    const form = document.querySelector('#examForm');

    const tick = () => {
      const m = Math.floor(seconds / 60);
      const s = seconds % 60;
      timerEl.textContent = `${m}:${String(s).padStart(2, '0')}`;
      if (seconds <= 0 && form) {
        form.submit();
        return;
      }
      seconds -= 1;
    };

    tick();
    setInterval(tick, 1000);

    let tabSwitches = 0;
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        tabSwitches += 1;
        fetch('index.php?page=student-monitor-event', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `event_type=tab_switch&details=Tab switched ${tabSwitches} times`
        });
      }
    });
  }
})();
