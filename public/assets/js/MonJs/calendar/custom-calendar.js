
/**
 * Custom Calendar Datepicker (Modifié)
 * 
 * Ce script implémente un calendrier personnalisé pour les champs de date
 * Fonctionnalités:
 * - Navigation mois/année
 * - Sélection de date
 * - Format de date personnalisé (DD/MM/YYYY)
 * - Navigation entre mois en cliquant sur les jours en dehors du mois actuel
 * - Le premier jour du mois suivant sera coloré en bleu uniquement après avoir cliqué sur le "1" du mois suivant
 */

$(document).ready(function() {
    // Variable globale pour suivre si nous devons mettre en surbrillance le premier jour du mois
    var highlightFirstDay = false;
    
    // Fonction pour créer un calendrier personnalisé
    function initCustomDatepicker(selector) {
        var today = new Date();
        var currentMonth = today.getMonth();
        var currentYear = today.getFullYear();
        
        // Élément datepicker
        var $datepicker = $(selector);
        
        // Initialiser avec la date actuelle du champ s'il en a une
        var initialDateStr = $datepicker.val();
        if (initialDateStr) {
            var initialDate = parseDate(initialDateStr);
            if (initialDate) {
                currentMonth = initialDate.getMonth();
                currentYear = initialDate.getFullYear();
            }
        }
        
        // Conteneur du calendrier
        var $calendar = $('<div class="custom-calendar-container"></div>');
        var $calendarWrapper = $('<div class="custom-calendar-wrapper"></div>');
        
        // En-tête avec mois et navigation
        var $header = $('<div class="calendar-header"></div>');
        var $prevBtn = $('<button type="button" class="calendar-nav-btn prev-btn"><i class="fa fa-chevron-left"></i></button>');
        var $nextBtn = $('<button type="button" class="calendar-nav-btn next-btn"><i class="fa fa-chevron-right"></i></button>');
        var $monthYear = $('<div class="month-year">APRIL, 2025</div>');
        
        $header.append($prevBtn).append($monthYear).append($nextBtn);
        
        // Corps du calendrier avec jours de la semaine
        var $calendarBody = $('<div class="calendar-body"></div>');
        var $weekdays = $('<div class="weekdays"></div>');
        var weekdays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
        
        weekdays.forEach(function(day) {
            $weekdays.append('<div class="weekday">' + day + '</div>');
        });
        
        // Grille des jours
        var $daysGrid = $('<div class="days-grid"></div>');
        
        $calendarBody.append($weekdays).append($daysGrid);
        $calendarWrapper.append($header).append($calendarBody);
        $calendar.append($calendarWrapper);
        
        // Ajouter le style CSS pour la mise en évidence bleue
        if (!document.getElementById('custom-calendar-styles')) {
            var styleElement = document.createElement('style');
            styleElement.id = 'custom-calendar-styles';
            styleElement.textContent = `
                .highlight-blue {
                    background-color: #4481dd !important;
                    color: white !important;
                }
            `;
            document.head.appendChild(styleElement);
        }
        
        function renderCalendar(month, year) {
            $monthYear.text(getMonthName(month) + ', ' + year);
            $daysGrid.empty();
            
            var firstDay = new Date(year, month, 1).getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var daysInPrevMonth = new Date(year, month, 0).getDate();
            
            // Obtenir la date actuellement sélectionnée (si elle existe)
            var selectedDateStr = $datepicker.val();
            var selectedDate = selectedDateStr ? parseDate(selectedDateStr) : null;
            
            // Jours du mois précédent
            for (var i = firstDay - 1; i >= 0; i--) {
                var dayNum = daysInPrevMonth - i;
                var prevMonth = month - 1;
                var prevYear = year;
                
                if (prevMonth < 0) {
                    prevMonth = 11;
                    prevYear--;
                }
                
                var $dayEl = $('<div class="day prev-month">' + dayNum + '</div>');
                
                // Permettre la navigation vers le mois précédent
                $dayEl.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var day = parseInt($(this).text());
                    
                    // Réinitialiser la surbrillance lors de la navigation
                    highlightFirstDay = false;
                    
                    goToPrevMonth(day);
                });
                
                $daysGrid.append($dayEl);
            }
            
            // Jours du mois actuel
            for (var day = 1; day <= daysInMonth; day++) {
                var date = new Date(year, month, day);
                var isToday = date.toDateString() === today.toDateString();
                var isDisabled = date < today && !isToday;
                
                // Vérifier si ce jour correspond à la date sélectionnée
                var isSelected = selectedDate && 
                                date.getDate() === selectedDate.getDate() && 
                                date.getMonth() === selectedDate.getMonth() && 
                                date.getFullYear() === selectedDate.getFullYear();
                
                // Si c'est le premier jour du mois et que highlightFirstDay est activé
                var shouldHighlight = day === 1 && highlightFirstDay;
                
                var $dayEl = $('<div class="day' + 
                    (isToday ? ' today' : '') +
                    (isDisabled ? ' disabled' : '') +
                    (isSelected ? ' selected' : '') +
                    (shouldHighlight ? ' highlight-blue' : '') +
                    ' current-month">' + day + '</div>');
                
                if (!isDisabled) {
                    $dayEl.on('click', function() {
                        // Retirer la classe highlight-blue de tous les jours
                        $daysGrid.find('.day').removeClass('highlight-blue');
                        
                        // Retirer la classe selected de tous les jours
                        $daysGrid.find('.day').removeClass('selected');
                        
                        // Ajouter la classe selected au jour cliqué
                        $(this).addClass('selected');
                        
                        // Désactiver la surbrillance du premier jour
                        highlightFirstDay = false;
                        
                        var selectedDay = parseInt($(this).text());
                        var selectedDate = new Date(year, month, selectedDay);
                        $datepicker.val(formatDate(selectedDate));
                        $calendar.hide();
                        
                        // Trigger change event pour mettre à jour les validations
                        $datepicker.trigger('change');
                    });
                }
                
                $daysGrid.append($dayEl);
            }
            
            // Jours du mois suivant
            var totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;
            var remainingCells = totalCells - (firstDay + daysInMonth);
            
            for (var j = 1; j <= remainingCells; j++) {
                var nextMonth = month + 1;
                var nextYear = year;
                
                if (nextMonth > 11) {
                    nextMonth = 0;
                    nextYear++;
                }
                
                var $dayEl = $('<div class="day next-month">' + j + '</div>');
                
                // Pour le premier jour du mois suivant, ajouter une classe spéciale
                if (j === 1) {
                    $dayEl.addClass('first-day-next-month');
                }
                
                // Permettre la navigation vers le mois suivant
                $dayEl.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var day = parseInt($(this).text());
                    
                    // Si c'est le premier jour du mois suivant, activer le drapeau
                    if ($(this).hasClass('first-day-next-month')) {
                        highlightFirstDay = true;
                    } else {
                        highlightFirstDay = false;
                    }
                    
                    goToNextMonth(day);
                });
                
                $daysGrid.append($dayEl);
            }
        }
        
        // Fonctions de navigation entre les mois
        function goToPrevMonth(day) {
            var newMonth = currentMonth - 1;
            var newYear = currentYear;
            
            if (newMonth < 0) {
                newMonth = 11;
                newYear--;
            }
            
            currentMonth = newMonth;
            currentYear = newYear;
            
            renderCalendar(currentMonth, currentYear);
        }
        
        function goToNextMonth(day) {
            var newMonth = currentMonth + 1;
            var newYear = currentYear;
            
            if (newMonth > 11) {
                newMonth = 0;
                newYear++;
            }
            
            currentMonth = newMonth;
            currentYear = newYear;
            
            renderCalendar(currentMonth, currentYear);
        }
        
        // Navigation
        $prevBtn.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Réinitialiser la surbrillance lors de la navigation manuelle
            highlightFirstDay = false;
            
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });
        
        $nextBtn.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Réinitialiser la surbrillance lors de la navigation manuelle
            highlightFirstDay = false;
            
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });
        
        // Afficher/masquer le calendrier
        $datepicker.on('focus', function() {
            // Fermer tous les autres calendriers ouverts
            $('.custom-calendar-container').hide();
            
            var offset = $datepicker.offset();
            var height = $datepicker.outerHeight();
            
            $calendar.css({
                top: offset.top + height + 5,
                left: offset.left
            }).show();
            
            // Si une date est déjà sélectionnée, afficher ce mois-là
            var dateStr = $datepicker.val();
            if (dateStr) {
                var date = parseDate(dateStr);
                if (date) {
                    currentMonth = date.getMonth();
                    currentYear = date.getFullYear();
                }
            }
            
            renderCalendar(currentMonth, currentYear);
        });
        
        // Fermer le calendrier en cliquant ailleurs
        $(document).on('click', function(e) {
            if (!$calendar.is(e.target) && $calendar.has(e.target).length === 0 && 
                !$datepicker.is(e.target) && !$(e.target).hasClass('calendar-nav-btn') && 
                !$(e.target).hasClass('fa-chevron-left') && !$(e.target).hasClass('fa-chevron-right')) {
                $calendar.hide();
            }
        });
        
        // Ajouter le calendrier au DOM
        $('body').append($calendar);
        $calendar.hide();
    }
    
    // Fonctions utilitaires
    function getMonthName(month) {
        var months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
        return months[month];
    }
    
    function formatDate(date) {
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        
        return (day < 10 ? '0' + day : day) + '/' + (month < 10 ? '0' + month : month) + '/' + year;
    }
    
    function parseDate(dateStr) {
        if (!dateStr) return null;
        
        var parts = dateStr.split('/');
        if (parts.length !== 3) return null;
        
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }
    
    // Initialiser les datepickers personnalisés
    initCustomDatepicker('#start_date');
    initCustomDatepicker('#end_date');
});