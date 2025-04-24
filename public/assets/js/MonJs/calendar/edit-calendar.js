
// /**
//  * Custom Calendar Datepicker pour le formulaire d'édition
//  */
// $(document).ready(function() {
//     // Fonction pour créer un calendrier personnalisé
//     function initCustomDatepicker(selector) {
//         var today = new Date();
//         var currentMonth = today.getMonth();
//         var currentYear = today.getFullYear();
        
//         // Élément datepicker
//         var $datepicker = $(selector);
        
//         // Initialiser avec la date actuelle du champ s'il en a une
//         var initialDateStr = $datepicker.val();
//         if (initialDateStr) {
//             var initialDate = parseDate(initialDateStr);
//             if (initialDate) {
//                 currentMonth = initialDate.getMonth();
//                 currentYear = initialDate.getFullYear();
//             }
//         }
        
//         // Conteneur du calendrier
//         var $calendar = $('<div class="custom-calendar-container"></div>');
//         var $calendarWrapper = $('<div class="custom-calendar-wrapper"></div>');
        
//         // En-tête avec mois et navigation
//         var $header = $('<div class="calendar-header"></div>');
//         var $prevBtn = $('<button type="button" class="calendar-nav-btn prev-btn"><i class="fa fa-chevron-left"></i></button>');
//         var $nextBtn = $('<button type="button" class="calendar-nav-btn next-btn"><i class="fa fa-chevron-right"></i></button>');
//         var $monthYear = $('<div class="month-year"></div>');
        
//         $header.append($prevBtn).append($monthYear).append($nextBtn);
        
//         // Corps du calendrier avec jours de la semaine
//         var $calendarBody = $('<div class="calendar-body"></div>');
//         var $weekdays = $('<div class="weekdays"></div>');
//         var weekdays = ['D', 'L', 'M', 'M', 'J', 'V', 'S']; // Jours en français
        
//         weekdays.forEach(function(day) {
//             $weekdays.append('<div class="weekday">' + day + '</div>');
//         });
        
//         // Grille des jours
//         var $daysGrid = $('<div class="days-grid"></div>');
        
//         $calendarBody.append($weekdays).append($daysGrid);
//         $calendarWrapper.append($header).append($calendarBody);
//         $calendar.append($calendarWrapper);
//         // Modifier la fonction renderCalendar pour désactiver les jours passés
// function renderCalendar(month, year, highlightDay) {
//     $monthYear.text(getMonthName(month) + ', ' + year);
//     $daysGrid.empty();
    
//     var firstDay = new Date(year, month, 1).getDay();
//     var daysInMonth = new Date(year, month + 1, 0).getDate();
//     var daysInPrevMonth = new Date(year, month, 0).getDate();
    
//     // Obtenir la date actuellement sélectionnée (si elle existe)
//     var selectedDateStr = $datepicker.val();
//     var selectedDate = selectedDateStr ? parseDate(selectedDateStr) : null;
    
//     // Jours du mois précédent
//     for (var i = firstDay - 1; i >= 0; i--) {
//         var dayNum = daysInPrevMonth - i;
//         var prevMonth = month - 1;
//         var prevYear = year;
        
//         if (prevMonth < 0) {
//             prevMonth = 11;
//             prevYear--;
//         }
        
//         (function(day, pMonth, pYear) { // Closure pour capturer les valeurs
//             var dayDate = new Date(pYear, pMonth, day);
//             var isPastDay = dayDate < new Date(today.getFullYear(), today.getMonth(), today.getDate());
            
//             var $dayEl = $('<div class="day prev-month' + (isPastDay ? ' disabled' : '') + '">' + day + '</div>');
            
//             if (!isPastDay) {
//                 $dayEl.on('click', function(e) {
//                     e.stopPropagation(); // Empêcher la propagation
                    
//                     // Mettre à jour la valeur du datepicker
//                     var selectedDate = new Date(pYear, pMonth, day);
//                     $datepicker.val(formatDate(selectedDate));
//                     $datepicker.trigger('change');
                    
//                     // Mettre à jour le mois courant et afficher le nouveau mois
//                     currentMonth = pMonth;
//                     currentYear = pYear;
//                     renderCalendar(currentMonth, currentYear, day);
//                 });
//             }
            
//             $daysGrid.append($dayEl);
//         })(dayNum, prevMonth, prevYear);
//     }
    
//     // Jours du mois actuel
//     for (var day = 1; day <= daysInMonth; day++) {
//         (function(d) { // Closure pour capturer le jour
//             var date = new Date(year, month, d);
//             var isToday = date.toDateString() === today.toDateString();
//             var isPastDay = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());
            
//             // Vérifier si ce jour correspond à la date sélectionnée
//             var isSelected = (highlightDay && d === highlightDay) || 
//                             (selectedDate && 
//                             date.getDate() === selectedDate.getDate() && 
//                             date.getMonth() === selectedDate.getMonth() && 
//                             date.getFullYear() === selectedDate.getFullYear());
            
//             var $dayEl = $('<div class="day' + 
//                 (isToday ? ' today' : '') +
//                 (isSelected ? ' selected' : '') +
//                 (isPastDay ? ' disabled' : '') +
//                 '">' + d + '</div>');
            
//             if (!isPastDay) {
//                 $dayEl.on('click', function(e) {
//                     e.stopPropagation(); // Empêcher la propagation
                    
//                     // Retirer la classe selected de tous les jours
//                     $daysGrid.find('.day').removeClass('selected');
//                     // Ajouter la classe selected au jour cliqué
//                     $(this).addClass('selected');
                    
//                     var selectedDate = new Date(year, month, d);
//                     $datepicker.val(formatDate(selectedDate));
//                     $calendar.hide();
                    
//                     // Trigger change event pour mettre à jour les validations
//                     $datepicker.trigger('change');
//                 });
//             }
            
//             $daysGrid.append($dayEl);
//         })(day);
//     }
    
//     // Jours du mois suivant
//     var totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;
//     var remainingCells = totalCells - (firstDay + daysInMonth);
    
//     var nextMonth = month + 1;
//     var nextYear = year;
    
//     if (nextMonth > 11) {
//         nextMonth = 0;
//         nextYear++;
//     }
    
//     for (var j = 1; j <= remainingCells; j++) {
//         (function(day, nMonth, nYear) { // Closure pour capturer les valeurs
//             var $dayEl = $('<div class="day next-month">' + day + '</div>');
            
//             $dayEl.on('click', function(e) {
//                 e.stopPropagation(); // Empêcher la propagation
                
//                 // Mettre à jour la valeur du datepicker
//                 var selectedDate = new Date(nYear, nMonth, day);
//                 $datepicker.val(formatDate(selectedDate));
//                 $datepicker.trigger('change');
                
//                 // Mettre à jour le mois courant et afficher le nouveau mois
//                 currentMonth = nMonth;
//                 currentYear = nYear;
//                 renderCalendar(currentMonth, currentYear, day);
//             });
            
//             $daysGrid.append($dayEl);
//         })(j, nextMonth, nextYear);
//     }
// }
        
        
//         // Navigation
//         $prevBtn.on('click', function(e) {
//             e.preventDefault();
//             e.stopPropagation();
//             currentMonth--;
//             if (currentMonth < 0) {
//                 currentMonth = 11;
//                 currentYear--;
//             }
//             renderCalendar(currentMonth, currentYear);
//         });
        
//         $nextBtn.on('click', function(e) {
//             e.preventDefault();
//             e.stopPropagation();
//             currentMonth++;
//             if (currentMonth > 11) {
//                 currentMonth = 0;
//                 currentYear++;
//             }
//             renderCalendar(currentMonth, currentYear);
//         });
        
//         // Afficher/masquer le calendrier
//         $datepicker.on('focus', function() {
//             // Fermer tous les autres calendriers ouverts
//             $('.custom-calendar-container').hide();
            
//             var offset = $datepicker.offset();
//             var height = $datepicker.outerHeight();
            
//             $calendar.css({
//                 top: offset.top + height + 5,
//                 left: offset.left
//             }).show();
            
//             // Si une date est déjà sélectionnée, afficher ce mois-là
//             var dateStr = $datepicker.val();
//             if (dateStr) {
//                 var date = parseDate(dateStr);
//                 if (date) {
//                     currentMonth = date.getMonth();
//                     currentYear = date.getFullYear();
//                 }
//             }
            
//             renderCalendar(currentMonth, currentYear);
//         });
        
//         // Empêcher la fermeture du calendrier en cliquant à l'intérieur
//         $calendar.on('click', function(e) {
//             e.stopPropagation();
//         });
        
//         // Fermer le calendrier en cliquant ailleurs
//         $(document).on('click', function(e) {
//             // Ne fermer que si le clic n'est pas sur un élément lié au calendrier
//             if (!$calendar.is(e.target) && $calendar.has(e.target).length === 0 && 
//                 !$datepicker.is(e.target)) {
//                 $calendar.hide();
//             }
//         });
        
//         // Ajouter le calendrier au DOM
//         $('body').append($calendar);
//         $calendar.hide();
//     }
    
//     // Fonctions utilitaires
//     function getMonthName(month) {
//         var months = ['JANVIER', 'FÉVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN', 'JUILLET', 'AOÛT', 'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DÉCEMBRE'];
//         return months[month];
//     }
    
//     function formatDate(date) {
//         var day = date.getDate();
//         var month = date.getMonth() + 1;
//         var year = date.getFullYear();
        
//         return (day < 10 ? '0' + day : day) + '/' + (month < 10 ? '0' + month : month) + '/' + year;
//     }
    
//     function parseDate(dateStr) {
//         if (!dateStr) return null;
        
//         var parts = dateStr.split('/');
//         if (parts.length !== 3) return null;
        
//         // Format DD/MM/YYYY
//         return new Date(parts[2], parts[1] - 1, parts[0]);
//     }
    
//     // Initialiser les datepickers personnalisés
//     initCustomDatepicker('#start_date');
//     initCustomDatepicker('#end_date');
    
//     // Validation de la date de fin après la date de début
//     $('#end_date').on('change', function() {
//         var startDateStr = $('#start_date').val();
//         var endDateStr = $(this).val();
        
//         if (startDateStr && endDateStr) {
//             var startDate = parseDate(startDateStr);
//             var endDate = parseDate(endDateStr);
            
//             if (startDate && endDate && endDate < startDate) {
//                 // Afficher un message d'erreur
//                 $(this).addClass('is-invalid');
//                 $(this).siblings('.invalid-feedback').text('La date de fin doit être après la date de début.');
//             } else {
//                 $(this).removeClass('is-invalid');
//             }
//         }
//     });
// });

/**
 * Custom Calendar Datepicker pour le formulaire d'édition
 */
$(document).ready(function() {
    // Fonction pour créer un calendrier personnalisé
    function initCustomDatepicker(selector) {
        var today = new Date();
        var currentMonth = today.getMonth();
        var currentYear = today.getFullYear();
        
        // Élément datepicker
        var $datepicker = $(selector);
        
        // Rendre le champ en lecture seule pour empêcher la modification directe
        $datepicker.attr('readonly', true);
        
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
        var $monthYear = $('<div class="month-year"></div>');
        
        $header.append($prevBtn).append($monthYear).append($nextBtn);
        
        // Corps du calendrier avec jours de la semaine
        var $calendarBody = $('<div class="calendar-body"></div>');
        var $weekdays = $('<div class="weekdays"></div>');
        var weekdays = ['D', 'L', 'M', 'M', 'J', 'V', 'S']; // Jours en français
        
        weekdays.forEach(function(day) {
            $weekdays.append('<div class="weekday">' + day + '</div>');
        });
        
        // Grille des jours
        var $daysGrid = $('<div class="days-grid"></div>');
        
        $calendarBody.append($weekdays).append($daysGrid);
        $calendarWrapper.append($header).append($calendarBody);
        $calendar.append($calendarWrapper);
        
        function renderCalendar(month, year, highlightDay) {
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
                
                (function(day, pMonth, pYear) { // Closure pour capturer les valeurs
                    var dayDate = new Date(pYear, pMonth, day);
                    var isPastDay = dayDate < new Date(today.getFullYear(), today.getMonth(), today.getDate());
                    
                    var $dayEl = $('<div class="day prev-month' + (isPastDay ? ' disabled' : '') + '">' + day + '</div>');
                    
                    if (!isPastDay) {
                        $dayEl.on('click', function(e) {
                            e.stopPropagation(); // Empêcher la propagation
                            
                            // Mettre à jour la valeur du datepicker
                            var selectedDate = new Date(pYear, pMonth, day);
                            $datepicker.val(formatDate(selectedDate));
                            $datepicker.trigger('change');
                            
                            // Mettre à jour le mois courant et afficher le nouveau mois
                            currentMonth = pMonth;
                            currentYear = pYear;
                            renderCalendar(currentMonth, currentYear, day);
                            $calendar.hide(); // Fermer le calendrier après sélection
                        });
                    }
                    
                    $daysGrid.append($dayEl);
                })(dayNum, prevMonth, prevYear);
            }
            
            // Jours du mois actuel
            for (var day = 1; day <= daysInMonth; day++) {
                (function(d) { // Closure pour capturer le jour
                    var date = new Date(year, month, d);
                    var isToday = date.toDateString() === today.toDateString();
                    var isPastDay = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());
                    
                    // Vérifier si ce jour correspond à la date sélectionnée
                    var isSelected = (highlightDay && d === highlightDay) || 
                                    (selectedDate && 
                                    date.getDate() === selectedDate.getDate() && 
                                    date.getMonth() === selectedDate.getMonth() && 
                                    date.getFullYear() === selectedDate.getFullYear());
                    
                    var $dayEl = $('<div class="day' + 
                        (isToday ? ' today' : '') +
                        (isSelected ? ' selected' : '') +
                        (isPastDay ? ' disabled' : '') +
                        '">' + d + '</div>');
                    
                    if (!isPastDay) {
                        $dayEl.on('click', function(e) {
                            e.stopPropagation(); // Empêcher la propagation
                            
                            // Retirer la classe selected de tous les jours
                            $daysGrid.find('.day').removeClass('selected');
                            // Ajouter la classe selected au jour cliqué
                            $(this).addClass('selected');
                            
                            var selectedDate = new Date(year, month, d);
                            $datepicker.val(formatDate(selectedDate));
                            $calendar.hide();
                            
                            // Trigger change event pour mettre à jour les validations
                            $datepicker.trigger('change');
                        });
                    }
                    
                    $daysGrid.append($dayEl);
                })(day);
            }
            
            // Jours du mois suivant
            var totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;
            var remainingCells = totalCells - (firstDay + daysInMonth);
            
            var nextMonth = month + 1;
            var nextYear = year;
            
            if (nextMonth > 11) {
                nextMonth = 0;
                nextYear++;
            }
            
            for (var j = 1; j <= remainingCells; j++) {
                (function(day, nMonth, nYear) { // Closure pour capturer les valeurs
                    var dayDate = new Date(nYear, nMonth, day);
                    var isPastDay = false; // Les jours du mois suivant ne sont généralement pas dans le passé
                    
                    var $dayEl = $('<div class="day next-month' + (isPastDay ? ' disabled' : '') + '">' + day + '</div>');
                    
                    if (!isPastDay) {
                        $dayEl.on('click', function(e) {
                            e.stopPropagation(); // Empêcher la propagation
                            
                            // Mettre à jour la valeur du datepicker
                            var selectedDate = new Date(nYear, nMonth, day);
                            $datepicker.val(formatDate(selectedDate));
                            $datepicker.trigger('change');
                            
                            // Mettre à jour le mois courant et afficher le nouveau mois
                            currentMonth = nMonth;
                            currentYear = nYear;
                            renderCalendar(currentMonth, currentYear, day);
                            $calendar.hide(); // Fermer le calendrier après sélection
                        });
                    }
                    
                    $daysGrid.append($dayEl);
                })(j, nextMonth, nextYear);
            }
        }
        
        // Navigation
        $prevBtn.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
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
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });
        
        // Afficher/masquer le calendrier
        $datepicker.on('click focus', function() {
            // Fermer tous les autres calendriers ouverts
            $('.custom-calendar-container').hide();
            
            var offset = $datepicker.offset();
            var height = $datepicker.outerHeight();
            
            // Positionner le calendrier pour qu'il s'affiche correctement
            $calendar.css({
                top: offset.top + height + 5,
                left: offset.left,
                position: 'absolute',
                zIndex: 9999, // Valeur élevée pour être sûr
                display: 'block',
                maxHeight: 'none', // Supprimer toute limite de hauteur
                overflow: 'visible' // Assurer que le contenu n'est pas coupé
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
        
        // Empêcher la fermeture du calendrier en cliquant à l'intérieur
        $calendar.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Fermer le calendrier en cliquant ailleurs
        $(document).on('click', function(e) {
            // Ne fermer que si le clic n'est pas sur un élément lié au calendrier
            if (!$calendar.is(e.target) && $calendar.has(e.target).length === 0 && 
                !$datepicker.is(e.target)) {
                $calendar.hide();
            }
        });
        
        // Ajouter le calendrier au DOM
        $('body').append($calendar);
        $calendar.hide();
    }
    
    // Fonctions utilitaires
    function getMonthName(month) {
        var months = ['JANVIER', 'FÉVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN', 'JUILLET', 'AOÛT', 'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DÉCEMBRE'];
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
        
        // Format DD/MM/YYYY
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }
    
    // Initialiser les datepickers personnalisés
    initCustomDatepicker('#start_date');
    initCustomDatepicker('#end_date');
    
    // Validation de la date de fin après la date de début
    $('#end_date').on('change', function() {
        var startDateStr = $('#start_date').val();
        var endDateStr = $(this).val();
        
        if (startDateStr && endDateStr) {
            var startDate = parseDate(startDateStr);
            var endDate = parseDate(endDateStr);
            
            if (startDate && endDate && endDate < startDate) {
                // Afficher un message d'erreur
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('La date de fin doit être après la date de début.');
            } else {
                $(this).removeClass('is-invalid');
            }
        }
    });
});