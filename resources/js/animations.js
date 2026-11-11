import anime from 'animejs';

const initializedTiles = new WeakSet();
const initializedButtons = new WeakSet();

export function initAnimations() {
    initTileAnimations();
    initButtonAnimations();
}

function initTileAnimations() {
    const tiles = document.querySelectorAll('.tile-anim');

    if (!tiles.length) {
        return;
    }

    anime({
        targets: tiles,
        scale: [0.8, 1],
        opacity: [0, 1],
        translateY: [20, 0],
        delay: anime.stagger(100),
        easing: 'easeOutElastic(1, .5)',
        duration: 900,
    });

    tiles.forEach((tile) => {
        if (initializedTiles.has(tile)) {
            return;
        }

        initializedTiles.add(tile);

        tile.addEventListener('mouseenter', () => {
            anime({
                targets: tile,
                scale: 1.02,
                duration: 180,
                easing: 'easeOutQuad',
            });
        });

        tile.addEventListener('mouseleave', () => {
            anime({
                targets: tile,
                scale: 1,
                duration: 180,
                easing: 'easeOutQuad',
            });
        });
    });
}

function initButtonAnimations() {
    const buttons = document.querySelectorAll('.btn-anim');

    buttons.forEach((button) => {
        if (initializedButtons.has(button)) {
            return;
        }

        initializedButtons.add(button);

        button.addEventListener('mouseenter', () => {
            if (button.disabled) {
                return;
            }

            anime({
                targets: button,
                scale: 1.05,
                duration: 180,
                easing: 'easeOutQuad',
            });
        });

        button.addEventListener('mouseleave', () => {
            anime({
                targets: button,
                scale: 1,
                duration: 180,
                easing: 'easeOutQuad',
            });
        });
    });
}
