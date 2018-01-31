(function($){
   var config; 
   var item;
   
   $.fn.extend({
      makeCalendar: function(options){
         var settings = {
            date_start: null, 
            date_end: null,
            GMT: '-06:00',
            path: "js/calendar/", 
            days_selected: [], 
            class_selected: 'calendar-day-selected',
            days_blocked: [], 
            class_blocked: 'calendar-day-blocked',
            class_normal: 'calendar-day-normal',
            other_days: [], 
            fn_select: null,
            fn_deselect: null,
            allow_weekend: true, 
            disabled_days: [], 
            comments: []
         };
         
         if(options)
             $.extend(settings, options);
         
         config = settings;
         
         var init = new Date(settings.date_start + 'T00:00:00');
         var finish = new Date(settings.date_end + 'T00:00:00');

         var months = new Array();
         months[1] = "Enero";
         months[2] = "Febrero";
         months[3] = "Marzo";
         months[4] = "Abril";
         months[5] = "Mayo";
         months[6] = "Junio";
         months[7] = "Julio";
         months[8] = "Agosto";
         months[9] = "Septiembre";
         months[10] = "Octubre";
         months[11] = "Noviembre";
         months[12] = "Diciembre";
         
         return this.each(function(){
             var date = new Date( init.getFullYear(), init.getMonth(), 1 );
             var end = new Date( finish.getFullYear(), finish.getMonth()+1, 0 );
             var this_day = 0;
             var this_month = 0;
             var this_year = 0;
             var last_date = 0;
             var day_of_week = 0;
             var html = "";
             var today = null;
             
             while(date <= end){
                 var cls = "";
                 today = date.getFullYear().toString() + "-" + (date.getMonth()+1<10?"0":"") + (date.getMonth()+1).toString() + "-" + (date.getDate()<10?"0":"") + date.getDate().toString();
                 this_day = date.getDate();
                 if(this_month != date.getMonth()+1){
                    this_year = date.getFullYear();
                    this_month = date.getMonth()+1;
                    var d = new Date(this_year, this_month , 0);
                    last_date = d.getDate();
                    day_of_week = date.getDay();
                    
                    html += (html ? "</tr></table></div></div>" : "") 
                         + "<div class = 'calendar-container'>"
                         + "<div class = 'calendar-title'>" + months[this_month] + "</div>"
                         + "<table class = 'calendar-month' id = '" + this_month + "'>"
                         + "<tr>"
                         + "<td class = 'calendar-titleday'>D</td>"
                         + "<td class = 'calendar-titleday'>L</td>"
                         + "<td class = 'calendar-titleday'>M</td>"
                         + "<td class = 'calendar-titleday'>X</td>"
                         + "<td class = 'calendar-titleday'>J</td>"
                         + "<td class = 'calendar-titleday'>V</td>"
                         + "<td class = 'calendar-titleday'>S</td>"
                         + "</tr>";
                    for(var i=0; i<day_of_week; i++)
                        html += "<td class = 'calendar-titleday'> </td>";
                 }
                 if(date < init || date > finish)
                     cls = settings.class_blocked;
                 else if(settings.disabled_days.indexOf(day_of_week) > -1)
                     cls = settings.class_blocked;
                 else if(!settings.allow_weekend && (day_of_week == 0 || day_of_week == 6) )
                     cls = settings.class_blocked;
                 else if(settings.days_blocked.indexOf( today ) > -1)
                     cls = settings.class_blocked;
                 else if(settings.days_selected.indexOf( today ) > -1)
                     cls = settings.class_selected;
                 else if(settings.other_days.length > 0){
                     $.each(settings.other_days, function(){
//                         console.log(this);
                         if(this.DATE == today)
                             cls = this.CLASS + ' ' + (this.SELECT?settings.class_normal:"");
                     });
                 }
                 html += "<td class = 'calendar-item " + (cls || settings.class_normal) + "' id = '" + today + "' title = '" + (settings.comments[today] || "") + "' >" + this_day + "</td>" ;
                 day_of_week++;
                 if(day_of_week > 6){
                     day_of_week = 0;
                     html += "</tr>";
                     if(this_day < last_date)
                         html += "<tr>";
                 }
                 date.setDate( date.getDate() + 1 );
             }
             
             html += "</tr></table></div></div>";
             $(this).html(html);
             
             $(this).find('.' + settings.class_normal + ', .' + settings.class_selected).hover(function(){
                $(this).addClass('calendar-hover'); 
                }, function(){
                    $(this).removeClass('calendar-hover'); 
                }
             );
     
             $(this).find('.calendar-item').click(function(day){
                var day = $(this).attr('id');
                if($(this).hasClass(settings.class_selected) && settings.fn_deselect){
                    item = $(this);
                    $(this).removeClass(settings.class_selected).addClass(settings.class_normal);
                    settings.fn_deselect(day);
                }else if($(this).hasClass(settings.class_normal) && settings.fn_select){
                    item = $(this);
                    $(this).removeClass(settings.class_normal).addClass(settings.class_selected);
                    settings.fn_select(day); 
                }
             });
             
         }); // END EACH
      },// END function
      
      getSelection: function(cls){
          return $(this).find('.' + config.class_selected + (cls?',.' + cls:"")).map(function(){
              return $(this).attr('id');
          }).get();
      }, 
              
      setSelection: function(dates){
          $(this).find('.' + config.class_selected).removeClass(config.class_selected).addClass(config.class_normal);
          if(dates){
              $.each(dates, function(d){
                  $(this).find('#' + d).not('.' + config.class_blocked).addClass(config.class_selected).removeClass(config.class_normal);
              });
          }
      },
      
      setBlocked: function(dates){
          $(this).find('.' + config.class_blocked).removeClass(config.class_blocked).addClass(config.class_normal);
          if(dates){
              $.each(dates, function(d){
                  $(this).find('#' + d).addClass(config.class_blocked).removeClass(config.class_normal);
              });
          }
      }, 
      
      preventLast: function(){
          $(item).removeClass(config.class_selected).addClass(config.class_normal);
      }

   }); // END extend
})(jQuery);