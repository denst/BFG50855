/*
 * <b>EventEmitter - класс для работы с событиями, подписка, удаление, посылка.<b/>
 */
var EventEmitter = function() {
    /*
     * var ee = EventEmitter.getInstance(); //получили ссылку на объект-евент емиттера.
     * Так же можно
     * var ee = new EventEmitte(); //Но это не тръ ООП. На работу самого емиттера не влияет.
     */
    EventEmitter.getInstance=function(){
        return new EventEmitter
    };
    //Ссыдка на самого себя
    var __instance,
    /*
     *  Преобразует строку в числовой хеш.
     */
    str2int=function (f){
        var a=f.length,e=2^a,d=0,c;
        while(a>=4){
            c=((f.charCodeAt(d)&255))|((f.charCodeAt(++d)&255)<<8)|((f.charCodeAt(++d)&255)<<16)| ((f.charCodeAt(++d)&255)<<24);
            c=(((c&65535)*1540483477)+((((c>>>16)*1540483477)&65535)<<16));
            c^=c>>>24;
            c=(((c&65535)*1540483477)+((((c>>>16)*1540483477)&65535)<<16));
            e=(((e&65535)*1540483477)+((((e>>>16)*1540483477)&65535)<<16))^c;
            a-=4;
            ++d
        }
        switch(a){
            case 3:
                e^=((f.charCodeAt(d+2)&255)<<16)>>>1;
            case 2:
                e^=((f.charCodeAt(d+1)&255)<<8)>>>0;
            case 1:
                e^=(f.charCodeAt(d)&255)>>>0;
                e=(((e&65535)*1540483477)+((((e>>>16)*1540483477)&65535)<<16))
        }
        e^=e>>>13;
        e=(((e&65535)*1540483477)+((((e>>>16)*1540483477)&65535)<<16));
        e^=e>>>15;
        return e>>>0
    },
    //Event:callback key:value
    __events={},
    __callbacksIds={},
    __future={},
    //JQuery listeners
    __listeners={},
    isDebug=false;

    function EventEmitter() {
        if (!__instance){
            __instance=this;
        }
        return __instance;
    }

    EventEmitter.prototype.runAction=function(){
        var args = Array.prototype.slice.call(arguments);
        for(var i in args){
            for(var j in __listeners[args[i]]){
                for(var u in __listeners[args[i]][j]){
                    if(u=='__init'){
                        if(isDebug)
                            console.log('JQ. Call init function for:'+j);
                        __listeners[args[i]][j][u]();
                    }else{
                        if(isDebug)
                            console.log('JQ.'+j+' binded for '+u+' event');
                        $(j).bind(u,__listeners[args[i]][j][u]);
                    }
                }
            }
        }

    }

    EventEmitter.prototype.register=function(processor,listener){
        __listeners[processor]=listener;
    }
   /*
    * Возвращае массив каллбак-фанкций для данного евента.<br/>
    * [] если нет каллбаков<br/>
    */
    EventEmitter.prototype.getAllListeners=function(event){
        return !__events[event]?[]:__events[event];
    }
    /*
    * Одиночное срабатывание на событие.
    * @event - event name
    * @callback - callback function
    */
    EventEmitter.prototype.once=function(event,callback){
        this.on(event, {
            once:callback
        })
    }
   /**
    * Посылка сообщнения в "будующие".<br/>
    * Если функция регистрируется на одноимённое событие, то она будет тут же вызванна с параметрами переданными в "будующие"<br/>
    * Пример:
    * Посылка 'myData' в будующие на событие 'evt'
    * future('evt','myData',2);<br/>
    * Спустя время N подписка некого каллбака на это событие.<br/>
    * on('evt',callback)<br/>
    * Произойдёт не только подписка, <b>но и одновременный вызов функции callback с параметрами 'myData'</b>
    * @param event имя события
    * @param data данныет
    * @param nums сколько раз сработать (-1 всегда)
    *
    */
    EventEmitter.prototype.future=function(event,data,nums){
        if(!event||!data||!nums)
            throw 'EventEmitter.future(event,data,nums) - (event data nums) must be defened';
        __future[event]={
            nums:nums,
            data:data
        };
    }
    /*
    * Подписка на событие<br/>
    * ee.on('event',callback);<br/>
    * ee.on('evt',function(data){console.log(data);});<br/>
    * ee.on('evt',function(){console.log('evt called');});<br/>
    * @param event - event name
    * @param callback - callback function<br/>
    * @return callback id<br/><br/>
    */
    EventEmitter.prototype.on=function(event,callback){
        event=event.split(',');
        for(var e in event){
            var evt=event[e].toString().trim();

            if(isDebug)
                console.log('Event '+evt+' binded for '+(typeof callback=='object'?' type is ONCE listener '/*+callback*/+'':' type is EVERY TIME listener: '/*+callback*/));
            if(!__events[evt]){
                __events[evt]=[];
                __callbacksIds[evt]=[];
            }
            var callInt=str2int(callback.toString());
            for(var i in __callbacksIds[evt]){
                if(__callbacksIds[evt][i]==callInt){
                    if(isDebug)
                        console.error('Attempt to duplicate callback: '+callback+'\nFor event: '+evt);
                    return __callbacksIds[evt][i];
                }

            }

            //Проверка, есть ли сообщения из будующего.
            if(__future[evt]!=null){
                if(__future[evt].nums==0){
                    __future[evt]=null;
                }else{
                    callback(__future[evt].data);
                    __future[evt]={
                        nums:--__future[evt].nums,
                        data:__future[evt].data
                    };
                }
            }
            __callbacksIds[evt].push(callInt);
            __events[evt].push(callback);
        }
        return callInt;
    }
    /**
     * Удаляет callback-функцию из подписки
     * @param event event name
     * @param id callback id
     */
    EventEmitter.prototype.clearCallback=function(event,id){
        if(!__callbacksIds[event]||!__events[event]){
            if(isDebug)
                console.log('Attempt to delete id: '+id+' from unknown event: '+event);
            return;
        }
        for(var i in __callbacksIds[event]){
            if(__callbacksIds[event][i]==id)
                delete __callbacksIds[event][i];
        }
        for(var i in __events[event]){
            if(str2int(__events[event][i].toString())==id)
                delete __events[event][i];
        }
    }
    /*
    * Удаление подписки. Удаляет  <b>ВСЕ</b> подписки на выбранное событие.
    * @param event event name</br>
    * ee.clearEvent('evt')
    */
    EventEmitter.prototype.clearEvent=function(event){
        if(isDebug)
            console.log('All listeners for event '+event+' deleted.')
        __callbacksIds[event]=[];
        __events[event]=[];
    }
    /*
    * Посылка события.
    * @param event event name
    * @param data (optional) <br/>
    * ee.emit('event',params);<br/>
    * ee.emit('evt',{val1:1,val2:2});<br/>
    */
    EventEmitter.prototype.emit=function(event,data){
        if(isDebug){
            console.log('Called event:'+event);
        }
        if(!__events[event])
            return;
        __events[event]=__events[event].map(function(callback){
            if(typeof callback=='function'){
                callback(data);
                return callback;
            }else if(typeof callback=='object'){
                callback.once(data);
            }
        });
    }
    return EventEmitter;
}();

    
$(window).resize(function() {
    var ee = EventEmitter.getInstance();
    ee.emit('ContentResized',{'width' : $(window).width(), 'height' : $(window).height()});
    // После загрузки страницы это же событие генерируется в navigation.js
});

