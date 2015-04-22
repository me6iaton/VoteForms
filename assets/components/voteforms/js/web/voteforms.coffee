VoteForms =
  initialize: ->
    if !jQuery().raty
      document.write '<script src="' +
        VoteFormsConfig.vendorUrl + 'raty/lib/jquery.raty.js"></script>'
    $(document).ready ->
      $('.raty').raty(
        starType: 'i'
        number: 10
        score: ->
          $(this).attr 'data-score'
      )
      return
    return



VoteForms.initialize()


(($) ->

) jQuery