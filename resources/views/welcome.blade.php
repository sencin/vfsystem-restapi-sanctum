<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>4D Cube with Drag (Responsive)</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --stroke: rgba(255, 255, 255, 0.3);
      --bg-color: #000;
    }

    body {
      margin: 0;
      height: 100vh;
      background: var(--bg-color);
      display: flex;
      justify-content: center;
      align-items: center;
      perspective: 1200px;
      overflow: hidden;
      touch-action: none;
    }

    .cube-container {
      width: 260px;
      height: 260px;
      transform-style: preserve-3d;
      position: relative;
      transform: rotateX(20deg) rotateY(0deg);
      transition: transform 0.1s ease-out;
    }

    .cube-face {
      position: absolute;
      width: 260px;
      height: 260px;
      background: transparent;
      border: 2px solid var(--stroke);
      box-sizing: border-box;
    }

    .face-1 { transform: rotateY(0deg) translateZ(130px); }
    .face-2 { transform: rotateY(90deg) translateZ(130px); }
    .face-3 { transform: rotateY(180deg) translateZ(130px); }
    .face-4 { transform: rotateY(-90deg) translateZ(130px); }
    .face-5 { transform: rotateX(90deg) translateZ(130px); }
    .face-6 { transform: rotateX(-90deg) translateZ(130px); }

    .label {
      position: fixed;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      color: white;
      font-family: 'Orbitron', sans-serif;
      font-size: 14px;
      letter-spacing: 1px;
      text-shadow: 0 0 4px rgba(255,255,255,0.3);
      pointer-events: none;
    }

    @media (max-width: 768px) {
      .cube-container {
        width: 180px;
        height: 180px;
      }

      .cube-face {
        width: 180px;
        height: 180px;
        border: 1.5px solid var(--stroke);
      }

      .face-1 { transform: rotateY(0deg) translateZ(90px); }
      .face-2 { transform: rotateY(90deg) translateZ(90px); }
      .face-3 { transform: rotateY(180deg) translateZ(90px); }
      .face-4 { transform: rotateY(-90deg) translateZ(90px); }
      .face-5 { transform: rotateX(90deg) translateZ(90px); }
      .face-6 { transform: rotateX(-90deg) translateZ(90px); }

      .label {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
  <div class="cube-container" id="cube">
    <div class="cube-face face-1"></div>
    <div class="cube-face face-2"></div>
    <div class="cube-face face-3"></div>
    <div class="cube-face face-4"></div>
    <div class="cube-face face-5"></div>
    <div class="cube-face face-6"></div>
  </div>

  <div class="label">API is Working</div>

  <script>
    const cube = document.getElementById('cube');
    let isDragging = false;
    let startX = 0, startY = 0;
    let currentX = 20, currentY = 0;
    let autoRotate = true;

    cube.addEventListener('pointerdown', (e) => {
      isDragging = true;
      startX = e.clientX;
      startY = e.clientY;
      autoRotate = false;
      cube.setPointerCapture(e.pointerId);
    });

    cube.addEventListener('pointermove', (e) => {
      if (!isDragging) return;
      const deltaX = e.clientX - startX;
      const deltaY = e.clientY - startY;
      currentY += deltaX * 0.5;
      currentX -= deltaY * 0.5;
      cube.style.transform = `rotateX(${currentX}deg) rotateY(${currentY}deg)`;
      startX = e.clientX;
      startY = e.clientY;
    });

    cube.addEventListener('pointerup', () => {
      isDragging = false;
      autoRotate = true;
    });

    function spin() {
      if (autoRotate) {
        currentY += 0.2;
        cube.style.transform = `rotateX(${currentX}deg) rotateY(${currentY}deg)`;
      }
      requestAnimationFrame(spin);
    }

    spin();
  </script>
</body>
</html>
