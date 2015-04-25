# A class-based template for jQuery plugins in Coffeescript
#
#     $('.target').VoteForm({ paramA: 'not-foo' });
#     $('.target').VoteForm('myMethod', 'Hello, world');
#
# Check out Alan Hogan's original jQuery plugin template:
# https://github.com/alanhogan/Coffeescript-jQuery-Plugin-Template
#
do($ = window.jQuery, window) ->

  # Define the plugin class
  class VoteForm

    defaults:
      selectors:
        container: '.vtf'
        submit: '.vtf-submit'
        raty: '.raty'
        iconDone: '.icon-done'
        alert:
          conteiner: '.vtf-alert'
          message: '.vtf-message'
          close: '.close'

    constructor: (el, options) ->
      @options = $.extend({}, @defaults, options)
      @selectors = @options.selectors
      @$el = $(el)
      @$ratys = @$el.find(@selectors.raty)
      @$submit = @$el.find(@selectors.submit)
      @data =
        action: 'record/record_multiple'
        form: @$el.data('form')
        thread: @$el.data('thread')
        fields: []
        fields: jQuery.makeArray @$ratys.map ()->
          score = +$(@).data('score')
          id = +$(@).data('id')
          return  {id: id, value: score} if score and id
    # Additional plugin methods go here
    init: () ->
      if !jQuery().raty
        document.write '<script src="' +
          @options.vendorUrl + 'raty/lib/jquery.raty.js"></script>'
      @_listeners()
      @_ready()

    # private methods
    _listeners: ->
      $(document).on 'submit', @selectors.container, (e) ->
        e.preventDefault()
        return false

      @$el.on 'click', @selectors.alert.close, () =>
        @$el.find(@selectors.alert.conteiner).hide()

      @$el.on 'click', @selectors.raty, (e) =>
        if(!@$submit.length)
          $this = $(e.currentTarget)
          @_sendRecors([{id: $this.data('id'), value: $this.raty('score')}], e.currentTarget)
        ###validation submit###
        if (@data.fields.length) == @$ratys.length
          @$submit.removeAttr('disabled')

      @$el.on 'click', @selectors.submit, (e) =>
        @_sendRecors(
          jQuery.makeArray @$ratys.map ()->
            score = +$(@).raty('score')
            id = +$(@).data('id')
            return  {id: id, value: score} if score and id
        , e.currentTarget)
        return

    _ready: ->
      $(document).ready =>
        @$ratys.raty
          starType: 'i'
          number: 10
          score: ->
            $(@).attr 'data-score'

    _sendRecors: (fields, currentTarget) =>
      @$el.find(@selectors.alert.conteiner).hide()
      @$el.find(@selectors.alert.message).html('')
      @currentTarget = currentTarget
      $.ajax
        url: @options.actionUrl
        type: 'post'
        dataType: 'json'
        data: $.extend(@data, {fields: fields})
      .done @_showDone
      .fail @_showFail
      return

    _showDone: (data) =>
      $iconDone = $(@currentTarget).parent().find(@selectors.iconDone)
      $iconDone.show(() =>
        setTimeout( ()=>
          $iconDone.hide()
        , 500 )
      )
      return

    _showFail: (err) =>
      message = if err.responseJSON?.message then err.responseJSON?.message +  '<br>' else ''
      if err.responseJSON?.errors
        jQuery.each err.responseJSON.errors, ()->
          message = message + @msg + '<br>'
      message = err.responseText if !message
      message = 'неизвестная ошибка' if !message
      @$el.find(@selectors.alert.message).html(message)
      @$el.find(@selectors.alert.conteiner).show()



  # Define the plugin
  $.fn.extend voteForm: (option, args...) ->
    @each ->
      $this = $(this)
      data = $this.data('VoteForm')
      if !data
        $this.data 'VoteForm', (data = new VoteForm(this, option))
        data.init()
      if typeof option == 'string'
        data[option].apply(data, args)

#  init
$('.vtf').voteForm(VoteFormsConfig)