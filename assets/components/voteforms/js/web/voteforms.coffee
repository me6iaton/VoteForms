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
        alert:
          conteiner: '.vtf-alert'
          message: '.vtf-message'
          close: '.close'

    constructor: (el, options) ->
      @options = $.extend({}, @defaults, options)
      @selectors = @options.selectors
      @$el = $(el)
      @$ratys = @$el.find(@selectors.raty)
      @data =
        form: @$el.data('form')
        thread: @$el.data('thread')
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
        ###store @data.fields ###
        $this = $(e.currentTarget)
        score = +$this.raty('score')
        id = +$this.data('id')
        if score and id
          @data.fields = @data.fields.filter () ->
            @.id != id
          @data.fields.push({id: id, value: score})
        ###validation submit###
        if (@data.fields.length) == @$ratys.length
          @$el.find(@selectors.submit).removeAttr('disabled')

      @$el.on 'click', @selectors.submit, () =>
        @$el.find(@selectors.alert.conteiner).hide()
        @$el.find(@selectors.alert.message).html('')
        $.ajax
          url: @options.actionUrl
          type: 'post'
          dataType: 'json'
          data: $.extend({action: 'record/create_multiple'}, @data)
        .done (data) ->
          stop
          return
        .fail @_showError
        return

    _ready: ->
      $(document).ready =>
        @$ratys.raty
          starType: 'i'
          number: 10
          score: ->
            $(@).attr 'data-score'

    _showError: (err) =>
      message = if err.responseJSON?.message then err.responseJSON?.message +  '<br>' else ''
      if err.responseJSON?.errors
        jQuery.each err.responseJSON.errors, ()->
          message = message + @msg + '<br>'
      message = 'неизвестная ошибка' if !message
      @$el.find(@selectors.alert.message).html(message)
      @$el.find(@selectors.alert.conteiner).show()



  # Define the plugin and init
  $.fn.extend voteForm: (option, args...) ->
    @each ->
      $this = $(this)
      data = $this.data('VoteForm')
      if !data
        $this.data 'VoteForm', (data = new VoteForm(this, option))
        data.init()
      if typeof option == 'string'
        data[option].apply(data, args)

$('.vtf').voteForm(VoteFormsConfig)