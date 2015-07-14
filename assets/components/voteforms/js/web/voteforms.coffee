# A class-based template for jQuery plugins in Coffeescript
#
#     $('.target').VoteForm({ paramA: 'not-foo' });
#     $('.target').VoteForm('myMethod', 'Hello, world');
#
# Check out Alan Hogan's original jQuery plugin template:
# https://github.com/alanhogan/Coffeescript-jQuery-Plugin-Template
#
do($ = window.jQuery, window) ->

  moduleKeywords = ['extended', 'included']

  class Module
    @extend: (obj) ->
      for key, value of obj when key not in moduleKeywords
        @[key] = value
      obj.extended?.apply(@)
      @

    @include: (obj) ->
      for key, value of obj when key not in moduleKeywords
        @::[key] = value
      obj.included?.apply(@)
      @

  VoteForm =
    defaults:
      selectors:
        container: '.vtf'
        submit: '.vtf-submit'
        rating: '.vtf-field-rating'
        usersCount: '.vtf-field-users-count'
        raty:
          all: '.raty'
          active: ".raty:not('.read-only')"
        upvote: '.vtf__upvote'
        iconDone: '.icon-done'
        alert:
          conteiner: '.vtf-alert'
          message: '.vtf-message'
          close: '.close'

    _sendRecors: (fields, currentTarget) ->
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

    _showFail: (err) ->
      message = if err.responseJSON?.message then err.responseJSON?.message + '<br>' else ''
      if err.responseJSON?.errors
        jQuery.each err.responseJSON.errors, ()->
          message = message + @msg + '<br>'
      message = err.responseText if !message
      message = 'неизвестная ошибка' if !message
      @$el.find(@selectors.alert.message).html(message)
      @$el.find(@selectors.alert.conteiner).show()


  class RatyVoteForm extends Module
    @include VoteForm

    constructor: (el, options) ->
      @options = $.extend({}, @defaults, options)
      @selectors = @options.selectors
      @$el = $(el)
      @$ratys = @$el.find(@selectors.raty.all)
      @$submit = @$el.find(@selectors.submit)
      @ratingMax = @$el.data('ratingMax')
      @data =
        action: 'record/record_multiple'
        form: @$el.data('form')
        thread: @$el.data('thread')
        fields: jQuery.makeArray @$ratys.map ()->
          score = +$(@).data('score')
          id = +$(@).data('id')
          return  {id: id, value: score} if score and id
      @dataShowRating =
        action: 'thread/get'
        id: @data.thread
      @$elsShowRating = $('.vtf-thread-'+@data.thread)

    # Additional plugin methods go here
    init: () ->
      if !jQuery().raty and !RatyVoteForm.init
        RatyVoteForm.init = true
        document.write '<script src="' +
          @options.vendorUrl + 'raty/lib/jquery.raty.js"></script>'
      @_listeners()
      @_ready()

    # private methods
    _listeners: ->
      if (@$submit.length)
        $(document).on 'submit', @selectors.container, (e) ->
          e.preventDefault()
          return false
        @$el.on 'click', @selectors.submit, (e) =>
          @_sendRecors(
            jQuery.makeArray @$ratys.map ()->
              score = +$(@).raty('score')
              id = +$(@).data('id')
              return  {id: id, value: score} if score and id
          , e.currentTarget)
          return
        @$el.on 'click', @selectors.raty.active, (e) =>
          #validation submit
          if (@data.fields.length) == @$ratys.length
            @$submit.removeAttr('disabled')
      else
        @$el.on 'click', @selectors.raty.active, (e) =>
          $this = $(e.currentTarget)
          @_sendRecors([{id: $this.data('id'), value: $this.raty('score')}], e.currentTarget)


      @$el.on 'click', @selectors.alert.close, () =>
        @$el.find(@selectors.alert.conteiner).hide()

    _ready: ->
      $(document).ready =>
        @$ratys.raty
          starType: 'i'
          number: @ratingMax
          readOnly: ->
            $(@).attr 'data-read-only'
          score: ->
            $(@).attr 'data-score'

    _showDone: (data) =>
      @_showRating()
      $iconDone = $(@currentTarget).parent().find(@selectors.iconDone)
      $iconDone.show(() =>
        setTimeout( ()=>
          $iconDone.hide()
        , 500 )
      )
      return

    _showRating: () =>
      $.ajax
        url: @options.actionUrl
        type: 'post'
        dataType: 'json'
        data: @dataShowRating
      .done (data) =>
        @$elsShowRating.find(@selectors.raty.all).raty('set', {score: data.object.rating})
        @$elsShowRating.find(@selectors.rating).html(data.object.rating)
        @$elsShowRating.find(@selectors.usersCount).html(data.object.users_count)
        return
      .fail @_showFail
      return

  class UpVoteForm extends Module
    @include VoteForm

    constructor: (el, options) ->
      @options = $.extend({}, @defaults, options)
      @selectors = @options.selectors
      @$el = $(el)
      @$upvotes = @$el.find(@selectors.upvote)
      @data =
        action: 'record/record_multiple'
        form: @$el.data('form')
        thread: @$el.data('thread')
        fields: []

    # Additional plugin methods go here
    init: () ->
      if !jQuery().upvote and !UpVoteForm.init
        UpVoteForm.init = true
        document.write '<script src="' +
            @options.vendorUrl + 'jquery-upvote/lib/jquery.upvote.js"></script>'
      @_ready()

    _ready: ->
      $(document).ready =>
        self = @
        @$upvotes.upvote(
          callback: () ->
            self._sendRecors([{id: @.id, value: @.count}])
        )
    _showDone: (data) =>
#      console.log data

  # Define the plugin
  $.fn.extend voteForm: (option, args...) ->
    @each ->
      $this = $(this)
      voteform = $this.data('VoteForm')
      if !voteform
        widget = $this.data 'widget'
        switch widget
          when 'raty'
            $this.data 'VoteForm', (voteform = new RatyVoteForm(this, option))
          when 'upvote'
            $this.data 'VoteForm', (voteform = new UpVoteForm(this, option))
          else
            $this.data 'VoteForm', (voteform = new RatyVoteForm(this, option))
        voteform.init()
      if typeof option == 'string'
        voteform[option].apply(voteform, args)

#  init
$('.vtf').voteForm(VoteFormsConfig)