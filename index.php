<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pernyataan Cinta Lucu</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="scene" id="scene">
    <div class="heart"></div>
    <div class="heart"></div>
    <div class="heart"></div>
    <div class="heart"></div>
    <div class="heart"></div>

    <h1 class="title" id="headline">Aku punya pertanyaan penting buat kamu...</h1>
    <p class="subtext" id="subtext">Klik tombol di bawah untuk tahu apa yang ingin aku katakan.</p>

    <div class="gif-wrapper">
      <img
        class="cute-gif"
        src="https://media.giphy.com/media/jUwpNzg9IcyrK/giphy.gif"
        alt="GIF kucing lofi lucu sedang memohon"
      />
    </div>

    <div class="buttons" id="buttonArea">
      <button class="btn btn-primary" data-action="open">Apa itu?</button>
    </div>

    <p class="mute-note">Suara romantis akan mulai setelah kamu memilih jawaban yang manis 💕</p>
  </div>

  <div class="modal" id="modal">
    <div class="modal-card">
      <p>Yakin? Coba pikir-pikir lagi... 🥺</p>
      <button class="modal-close" id="modalClose">Baiklah...</button>
    </div>
  </div>

  <script>
    const buttonArea = document.getElementById('buttonArea');
    const headline = document.getElementById('headline');
    const subtext = document.getElementById('subtext');
    const scene = document.getElementById('scene');
    const modal = document.getElementById('modal');
    const modalClose = document.getElementById('modalClose');
    let isNoClicked = false;
    let loveAudio;

    function createOpenButton() {
      buttonArea.innerHTML = '';
      const openBtn = document.createElement('button');
      openBtn.className = 'btn btn-primary';
      openBtn.textContent = 'Apa itu?';
      openBtn.dataset.action = 'open';
      buttonArea.appendChild(openBtn);
    }

    function clearLoveBanner() {
      const existingBanner = scene.querySelector('.love-banner');
      if (existingBanner) existingBanner.remove();
    }

    function resetScene() {
      headline.textContent = 'Aku punya pertanyaan penting buat kamu...';
      subtext.textContent = 'Klik tombol di bawah untuk tahu apa yang ingin aku katakan.';
      scene.classList.remove('love-mode');
      clearLoveBanner();
      isNoClicked = false;
      if (loveAudio) {
        loveAudio.stop();
        loveAudio = null;
      }
      createOpenButton();
    }

    function showQuestion() {
      headline.textContent = 'Kamu mau gak jadi pacar aku?';
      subtext.textContent = 'Kalau kamu mau, aku janji bakal selalu lucu dan sayang terus.';
      buttonArea.innerHTML = '';

      const yesBtn = document.createElement('button');
      yesBtn.className = 'btn btn-primary';
      yesBtn.textContent = 'Mau Banget! 🧸';
      yesBtn.dataset.action = 'yes';

      const noBtn = document.createElement('button');
      noBtn.className = 'btn btn-secondary';
      noBtn.textContent = 'Enggak 🥺';
      noBtn.dataset.action = 'no';
      noBtn.id = 'noBtn';

      buttonArea.appendChild(yesBtn);
      buttonArea.appendChild(noBtn);
    }

    function rejectLove() {
      const noBtn = document.getElementById('noBtn');
      if (isNoClicked) return;
      if (!noBtn) return;
      isNoClicked = true;
      noBtn.disabled = true;
      modal.classList.add('show');
    }

    function acceptLove() {
      headline.textContent = 'Yay! Aku bahagia banget kamu mau! 💖';
      subtext.textContent = 'Kamu sudah membuat hari aku jadi sempurna.';
      buttonArea.innerHTML = '';
      scene.classList.add('love-mode');
      createLoveBanner();
      playRomanticMelody();
    }

    function createLoveBanner() {
      clearLoveBanner();
      const message = document.createElement('p');
      message.className = 'love-banner';
      message.textContent = '✨ Selamat datang di dunia kita yang penuh cinta! ✨';
      message.style.marginTop = '18px';
      message.style.fontWeight = '700';
      message.style.color = '#c72f7f';
      subtext.after(message);
    }

    function playRomanticMelody() {
      if (!window.AudioContext && !window.webkitAudioContext) return;
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      const audioCtx = new AudioContext();
      const notes = [261.63, 311.13, 349.23, 392.00, 440.00, 523.25];
      let time = audioCtx.currentTime;

      loveAudio = {
        stop: () => {
          if (audioCtx.state !== 'closed') {
            audioCtx.close();
          }
        }
      };

      notes.forEach((freq) => {
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.value = freq;
        gain.gain.value = 0.05;
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start(time);
        osc.stop(time + 0.45);
        time += 0.45;
      });
    }

    buttonArea.addEventListener('click', (event) => {
      const action = event.target.dataset.action;
      if (!action) return;
      if (action === 'open') showQuestion();
      if (action === 'yes') acceptLove();
      if (action === 'no') rejectLove();
    });

    modalClose.addEventListener('click', () => {
      modal.classList.remove('show');
      setTimeout(resetScene, 250);
    });

    resetScene();
  </script>
</body>
</html>
