/**
 * BEO System - Event List & Management Interactive Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('slider');
    const searchInput = document.getElementById('search-input');
    const searchClearBtn = document.getElementById('search-clear-btn');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const noEventsNotice = document.getElementById('no-events-notice');
    const statTotal = document.getElementById('stat-total');
    const statActive = document.getElementById('stat-active');
    const heroActionBtn = document.getElementById('hero-action-btn');
    const heroTitle = document.getElementById('hero-title');
    const heroMeta = document.getElementById('hero-meta');
    const heroImg = document.getElementById('hero-img');
    const heroStatus = document.getElementById('hero-status');
    const heroDot = document.getElementById('hero-dot');
    const leftPanel = document.querySelector('.left-panel');

    // Calendar Elements
    const calMonthTitle = document.getElementById('cal-month-title');
    const calPrevBtn = document.getElementById('cal-prev-btn');
    const calNextBtn = document.getElementById('cal-next-btn');
    const calGridDates = document.getElementById('cal-grid-dates');

    // State
    let isUpdating = false;
    let currentFilterStatus = 'all';
    let currentSearchQuery = '';
    let selectedDate = null; // format: YYYY-MM-DD
    
    // Initial Calendar Month/Year
    // Default to the first event's month/year or September 2026
    let initialDate = new Date();
    const firstCard = document.querySelector('.event-card');
    if (firstCard && firstCard.dataset.rawDate) {
        const parts = firstCard.dataset.rawDate.split('-');
        if (parts.length === 3) {
            initialDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, 1);
        }
    }
    let currentCalMonth = initialDate.getMonth();
    let currentCalYear = initialDate.getFullYear();

    // Event dates array for calendar dots
    const eventDateMap = new Set();
    document.querySelectorAll('.event-card').forEach(card => {
        if (card.dataset.rawDate) {
            eventDateMap.add(card.dataset.rawDate);
        }
    });

    /**
     * Update Left Panel (Hero) with card data
     */
    function updateHeroPanel(card) {
        if (!card) return;

        const title = card.getAttribute('data-title') || '';
        const date = card.getAttribute('data-date') || '';
        const guests = card.getAttribute('data-guests') || '';
        const layout = card.getAttribute('data-layout') || '';
        const status = card.getAttribute('data-status') || '';
        const color = card.getAttribute('data-color') || '#3B82F6';
        const image = card.getAttribute('data-image') || '';
        const detailUrl = card.getAttribute('data-detail-url') || '#';

        // Update Background Image
        if (heroImg && image) {
            heroImg.src = image;
        }

        // Update Status & Dot
        if (heroStatus && heroDot) {
            heroStatus.style.color = color;
            const statusText = heroStatus.querySelector('span');
            if (statusText) statusText.innerText = status;
            heroDot.style.backgroundColor = color;
            heroDot.style.boxShadow = `0 0 10px ${color}`;
        }

        // Update Title & Meta
        if (heroTitle) heroTitle.innerHTML = title;
        if (heroMeta) {
            heroMeta.innerHTML = `Target: ${guests} &mdash; ${layout}<br>${date}`;
        }

        // Update Action Button Link
        if (heroActionBtn) {
            heroActionBtn.setAttribute('href', detailUrl);
        }

        // Subtle animation transition
        if (leftPanel) {
            leftPanel.style.opacity = '0.7';
            setTimeout(() => {
                leftPanel.style.opacity = '1';
                leftPanel.style.transition = 'opacity 0.35s ease';
            }, 40);
        }
    }

    /**
     * Focus and center an event card
     */
    window.focusCard = function(element) {
        if (!element || element.style.display === 'none') return;

        isUpdating = true;
        document.querySelectorAll('.event-card').forEach(c => c.classList.remove('active'));
        element.classList.add('active');

        element.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        updateHeroPanel(element);

        setTimeout(() => {
            isUpdating = false;
        }, 450);
    };

    /**
     * Filter & Search Evaluation
     */
    function applyFilterAndSearch() {
        const query = currentSearchQuery.trim().toLowerCase();
        let visibleCount = 0;
        let activeCount = 0;
        let firstVisibleCard = null;

        const allCards = document.querySelectorAll('.event-card');

        allCards.forEach(card => {
            const cardStatus = (card.getAttribute('data-status') || '').toLowerCase();
            const rawDate = card.getAttribute('data-raw-date') || '';
            const searchHaystack = (card.getAttribute('data-search-term') || '').toLowerCase();

            // Status Match
            let statusMatch = false;
            if (currentFilterStatus === 'all' || currentFilterStatus === 'semua') {
                statusMatch = true;
            } else if (currentFilterStatus === 'aktif' || currentFilterStatus === 'active') {
                statusMatch = (cardStatus === 'upcoming' || cardStatus === 'ongoing');
            } else if (currentFilterStatus === 'selesai') {
                statusMatch = (cardStatus === 'completed');
            } else {
                statusMatch = (cardStatus === currentFilterStatus);
            }

            // Search Match
            let searchMatch = true;
            if (query !== '') {
                searchMatch = searchHaystack.includes(query);
            }

            // Date Match
            let dateMatch = true;
            if (selectedDate !== null) {
                dateMatch = (rawDate === selectedDate);
            }

            // Combine
            if (statusMatch && searchMatch && dateMatch) {
                card.style.display = 'flex';
                visibleCount++;
                if (cardStatus === 'upcoming' || cardStatus === 'ongoing') {
                    activeCount++;
                }
                if (!firstVisibleCard) {
                    firstVisibleCard = card;
                }
            } else {
                card.style.display = 'none';
                card.classList.remove('active');
            }
        });

        // Update Dynamic Stats
        if (statTotal) statTotal.innerText = visibleCount;
        if (statActive) statActive.innerText = activeCount;

        // Toggle No Events Message
        if (noEventsNotice) {
            if (visibleCount === 0) {
                noEventsNotice.style.display = 'flex';
                if (slider) slider.style.display = 'none';
            } else {
                noEventsNotice.style.display = 'none';
                if (slider) slider.style.display = 'flex';
            }
        }

        // Focus first visible card or reset hero
        if (firstVisibleCard) {
            window.focusCard(firstVisibleCard);
        }
    }

    /**
     * Search Input Listeners
     */
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            currentSearchQuery = e.target.value;
            if (searchClearBtn) {
                if (currentSearchQuery.length > 0) {
                    searchClearBtn.classList.add('visible');
                } else {
                    searchClearBtn.classList.remove('visible');
                }
            }
            applyFilterAndSearch();
        });
    }

    if (searchClearBtn) {
        searchClearBtn.addEventListener('click', () => {
            if (searchInput) {
                searchInput.value = '';
                currentSearchQuery = '';
                searchClearBtn.classList.remove('visible');
                applyFilterAndSearch();
            }
        });
    }

    /**
     * Status Filter Buttons Listener
     */
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            currentFilterStatus = (btn.getAttribute('data-status') || 'all').toLowerCase();
            applyFilterAndSearch();
        });
    });

    /**
     * Reset Filter Helper
     */
    window.resetAllFilters = function() {
        if (searchInput) {
            searchInput.value = '';
            currentSearchQuery = '';
            if (searchClearBtn) searchClearBtn.classList.remove('visible');
        }
        currentFilterStatus = 'all';
        filterBtns.forEach(b => {
            if (b.getAttribute('data-status') === 'all') {
                b.classList.add('active');
            } else {
                b.classList.remove('active');
            }
        });
        selectedDate = null;
        renderCalendar(currentCalMonth, currentCalYear);
        applyFilterAndSearch();
    };

    /**
     * Card Slider Wheel & Scroll Listener
     */
    if (slider) {
        slider.addEventListener('scroll', () => {
            if (isUpdating) return;

            const visibleCards = Array.from(document.querySelectorAll('.event-card')).filter(c => c.style.display !== 'none');
            if (visibleCards.length === 0) return;

            let closestCard = visibleCards[0];
            let minDistance = Infinity;
            const sliderCenter = slider.getBoundingClientRect().left + (slider.offsetWidth / 2);

            visibleCards.forEach(card => {
                const cardCenter = card.getBoundingClientRect().left + (card.offsetWidth / 2);
                const distance = Math.abs(sliderCenter - cardCenter);
                if (distance < minDistance) {
                    minDistance = distance;
                    closestCard = card;
                }
            });

            if (!closestCard.classList.contains('active')) {
                visibleCards.forEach(c => c.classList.remove('active'));
                closestCard.classList.add('active');
                updateHeroPanel(closestCard);
            }
        });

        slider.addEventListener('wheel', (evt) => {
            if (evt.deltaY !== 0) {
                evt.preventDefault();
                slider.scrollLeft += evt.deltaY * 2.5;
            }
        }, { passive: false });
    }

    /**
     * --------------------------------------------------------------------------
     * Interactive Calendar Rendering
     * --------------------------------------------------------------------------
     */
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    function renderCalendar(month, year) {
        if (!calGridDates || !calMonthTitle) return;

        calMonthTitle.innerText = `${monthNames[month]} ${year}`;
        calGridDates.innerHTML = '';

        // Day of week for first day (0 = Sun, 1 = Mon ... 6 = Sat)
        // Convert to Monday = 0, Sunday = 6
        const firstDayObj = new Date(year, month, 1);
        let firstDayIndex = firstDayObj.getDay(); // 0 is Sunday
        firstDayIndex = (firstDayIndex === 0) ? 6 : firstDayIndex - 1;

        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
        const prevMonthLastDay = new Date(year, month, 0).getDate();

        // 1. Previous Month Dimmed Days
        for (let i = firstDayIndex; i > 0; i--) {
            const dayNum = prevMonthLastDay - i + 1;
            const div = document.createElement('div');
            div.className = 'date dim';
            div.innerText = dayNum;
            calGridDates.appendChild(div);
        }

        // 2. Current Month Days
        for (let day = 1; day <= totalDaysInMonth; day++) {
            const div = document.createElement('div');
            div.className = 'date';
            div.innerText = day;

            const mStr = String(month + 1).padStart(2, '0');
            const dStr = String(day).padStart(2, '0');
            const dateStr = `${year}-${mStr}-${dStr}`;

            div.dataset.date = dateStr;

            // Check if this date has event(s)
            if (eventDateMap.has(dateStr)) {
                div.classList.add('has-event');
            }

            // Check if this is the currently selected filter date
            if (selectedDate === dateStr) {
                div.classList.add('active');
            }

            // Click listener for date filtering
            div.addEventListener('click', () => {
                if (selectedDate === dateStr) {
                    // Deselect date filter
                    selectedDate = null;
                    div.classList.remove('active');
                } else {
                    selectedDate = dateStr;
                    document.querySelectorAll('#cal-grid-dates .date').forEach(d => d.classList.remove('active'));
                    div.classList.add('active');
                }
                applyFilterAndSearch();
            });

            calGridDates.appendChild(div);
        }

        // 3. Next Month Dimmed Days to fill grid up to 35 or 42 cells
        const totalRendered = firstDayIndex + totalDaysInMonth;
        const remainingCells = (totalRendered % 7 === 0) ? 0 : 7 - (totalRendered % 7);
        for (let j = 1; j <= remainingCells; j++) {
            const div = document.createElement('div');
            div.className = 'date dim';
            div.innerText = j;
            calGridDates.appendChild(div);
        }
    }

    if (calPrevBtn) {
        calPrevBtn.addEventListener('click', () => {
            currentCalMonth--;
            if (currentCalMonth < 0) {
                currentCalMonth = 11;
                currentCalYear--;
            }
            renderCalendar(currentCalMonth, currentCalYear);
        });
    }

    if (calNextBtn) {
        calNextBtn.addEventListener('click', () => {
            currentCalMonth++;
            if (currentCalMonth > 11) {
                currentCalMonth = 0;
                currentCalYear++;
            }
            renderCalendar(currentCalMonth, currentCalYear);
        });
    }

    // Initial render
    renderCalendar(currentCalMonth, currentCalYear);

    // Initial focus on the first active card
    const initialActive = document.querySelector('.event-card.active') || document.querySelector('.event-card');
    if (initialActive) {
        window.focusCard(initialActive);
    }
});
