 document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.genre-btn');
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                buttons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const genre = this.getAttribute('data-genre').toLowerCase();
                document.querySelectorAll('.comic-card').forEach(card => {
                    let cardGenres = card.getAttribute('data-genre').split(',');
                    if (genre === "all") {
                        card.style.display = "";
                    } else {
                        if (cardGenres.includes(genre)) {
                            card.style.display = "";
                        } else {
                            card.style.display = "none";
                        }
                    }
                });
            });
        });
    });