(() => {
    const input = document.getElementById('outlet-search');
    if (!input) return;

    const cards = Array.from(document.querySelectorAll('.outlet-card'));
    const count = document.getElementById('outlet-result-count');
    const empty = document.getElementById('outlet-no-results');
    const normalize = value => value.normalize('NFKC').toLocaleLowerCase().replace(/\s+/g, ' ').trim();
    const entries = cards.map(card => ({ card, text: normalize(card.dataset.outletSearch) }));

    function filter() {
        const terms = normalize(input.value).split(' ').filter(Boolean);
        let visible = 0;
        entries.forEach(({ card, text }) => {
            const matches = terms.every(term => text.includes(term));
            card.hidden = !matches;
            if (matches) visible++;
        });
        count.textContent = `${visible} of ${cards.length} outlets`;
        empty.hidden = visible !== 0 || cards.length === 0;
    }

    document.getElementById('outlet-search-controls').hidden = false;
    input.addEventListener('input', filter);
    filter();
})();
