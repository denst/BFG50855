EventEmitter.getInstance().register('timers', {
    '__timers':{
        __init:function(){
            EventEmitter.getInstance().once('PageChanged',function(){
                this.timers=[],
                this.getTime=new RegExp('([0-9]+):([0-9]+)'),
                this.checkHour=function(timer){
                    switch(parseInt(timer.hour)){
                        case 23:
                            timer.hour=0;
                            break;
                        default:
                            timer.hout++;
                    }
                    return timer;
                },
    
                this.checkMinute=function(timer){
                    switch(parseInt(timer.minute)){
                        case 59:
                            timer.minute=0;
                            timer.hour++;
                            break;
                        default:
                            timer.minute++;
                    }
                    return timer;
                },
                /*
                * Форматирует время в "красивый" формат, добавляя нолик впереди,если он необходим
                * Пример "01:02"
                * По логике, надо проверять не длинну знаения, а само значение.
                * val<10
                * Но, если передано значение "01", то оно меньше десяти, и отформатируется в
                * "001", что не есть хорошо.
                */
                this.format=function(val){
        
                    if(val.toString().length==1)
                        return '0'+val;
                    else
                        return val;
                };
                var $this=this;
                
                $('.time').each(function(){
                    var el=$(this);
                    if($this.getTime.test(el.text())){
                        $this.timers.push({
                            el:el,
                            hour:RegExp.$1,
                            minute:RegExp.$2
                        });
                    }
                });
                setInterval(function(){
                    $this.timers=$this.timers.map(function(timer,i){
                        $this.checkHour(timer);
                        $this.checkMinute(timer);
              
                        var txt=timer.el.text().toString().split(':')[0];

                        timer.el.text(txt+':'+$this.format(timer.hour)+':'+$this.format(timer.minute));
                        return timer;
                    })
                },60000); 
               
            });
        }
    }
});
EventEmitter.getInstance().runAction('timers');
