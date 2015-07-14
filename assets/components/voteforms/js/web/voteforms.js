// Generated by CoffeeScript 1.9.2
(function() {
  var indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; },
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    hasProp = {}.hasOwnProperty,
    slice = [].slice;

  (function($, window) {
    var Module, RatyVoteForm, UpVoteForm, VoteForm, moduleKeywords;
    moduleKeywords = ['extended', 'included'];
    Module = (function() {
      function Module() {}

      Module.extend = function(obj) {
        var key, ref, value;
        for (key in obj) {
          value = obj[key];
          if (indexOf.call(moduleKeywords, key) < 0) {
            this[key] = value;
          }
        }
        if ((ref = obj.extended) != null) {
          ref.apply(this);
        }
        return this;
      };

      Module.include = function(obj) {
        var key, ref, value;
        for (key in obj) {
          value = obj[key];
          if (indexOf.call(moduleKeywords, key) < 0) {
            this.prototype[key] = value;
          }
        }
        if ((ref = obj.included) != null) {
          ref.apply(this);
        }
        return this;
      };

      return Module;

    })();
    VoteForm = {
      defaults: {
        selectors: {
          container: '.vtf',
          submit: '.vtf-submit',
          rating: '.vtf-field-rating',
          usersCount: '.vtf-field-users-count',
          raty: {
            all: '.raty',
            active: ".raty:not('.read-only')"
          },
          upvote: '.vtf__upvote',
          iconDone: '.icon-done',
          alert: {
            conteiner: '.vtf-alert',
            message: '.vtf-message',
            close: '.close'
          }
        }
      },
      _sendRecors: function(fields, currentTarget) {
        this.$el.find(this.selectors.alert.conteiner).hide();
        this.$el.find(this.selectors.alert.message).html('');
        this.currentTarget = currentTarget;
        $.ajax({
          url: this.options.actionUrl,
          type: 'post',
          dataType: 'json',
          data: $.extend(this.data, {
            fields: fields
          })
        }).done(this._showDone).fail(this._showFail);
      },
      _showFail: function(err) {
        var message, ref, ref1, ref2;
        message = ((ref = err.responseJSON) != null ? ref.message : void 0) ? ((ref1 = err.responseJSON) != null ? ref1.message : void 0) + '<br>' : '';
        if ((ref2 = err.responseJSON) != null ? ref2.errors : void 0) {
          jQuery.each(err.responseJSON.errors, function() {
            return message = message + this.msg + '<br>';
          });
        }
        if (!message) {
          message = err.responseText;
        }
        if (!message) {
          message = 'неизвестная ошибка';
        }
        this.$el.find(this.selectors.alert.message).html(message);
        return this.$el.find(this.selectors.alert.conteiner).show();
      }
    };
    RatyVoteForm = (function(superClass) {
      extend(RatyVoteForm, superClass);

      RatyVoteForm.include(VoteForm);

      function RatyVoteForm(el, options) {
        this._showRating = bind(this._showRating, this);
        this._showDone = bind(this._showDone, this);
        this.options = $.extend({}, this.defaults, options);
        this.selectors = this.options.selectors;
        this.$el = $(el);
        this.$ratys = this.$el.find(this.selectors.raty.all);
        this.$submit = this.$el.find(this.selectors.submit);
        this.ratingMax = this.$el.data('ratingMax');
        this.data = {
          action: 'record/record_multiple',
          form: this.$el.data('form'),
          thread: this.$el.data('thread'),
          fields: jQuery.makeArray(this.$ratys.map(function() {
            var id, score;
            score = +$(this).data('score');
            id = +$(this).data('id');
            if (score && id) {
              return {
                id: id,
                value: score
              };
            }
          }))
        };
        this.dataShowRating = {
          action: 'thread/get',
          id: this.data.thread
        };
        this.$elsShowRating = $('.vtf-thread-' + this.data.thread);
      }

      RatyVoteForm.prototype.init = function() {
        if (!jQuery().raty && !RatyVoteForm.init) {
          RatyVoteForm.init = true;
          document.write('<script src="' + this.options.vendorUrl + 'raty/lib/jquery.raty.js"></script>');
        }
        this._listeners();
        return this._ready();
      };

      RatyVoteForm.prototype._listeners = function() {
        if (this.$submit.length) {
          $(document).on('submit', this.selectors.container, function(e) {
            e.preventDefault();
            return false;
          });
          this.$el.on('click', this.selectors.submit, (function(_this) {
            return function(e) {
              _this._sendRecors(jQuery.makeArray(_this.$ratys.map(function() {
                var id, score;
                score = +$(this).raty('score');
                id = +$(this).data('id');
                if (score && id) {
                  return {
                    id: id,
                    value: score
                  };
                }
              })), e.currentTarget);
            };
          })(this));
          this.$el.on('click', this.selectors.raty.active, (function(_this) {
            return function(e) {
              if (_this.data.fields.length === _this.$ratys.length) {
                return _this.$submit.removeAttr('disabled');
              }
            };
          })(this));
        } else {
          this.$el.on('click', this.selectors.raty.active, (function(_this) {
            return function(e) {
              var $this;
              $this = $(e.currentTarget);
              return _this._sendRecors([
                {
                  id: $this.data('id'),
                  value: $this.raty('score')
                }
              ], e.currentTarget);
            };
          })(this));
        }
        return this.$el.on('click', this.selectors.alert.close, (function(_this) {
          return function() {
            return _this.$el.find(_this.selectors.alert.conteiner).hide();
          };
        })(this));
      };

      RatyVoteForm.prototype._ready = function() {
        return $(document).ready((function(_this) {
          return function() {
            return _this.$ratys.raty({
              starType: 'i',
              number: _this.ratingMax,
              readOnly: function() {
                return $(this).attr('data-read-only');
              },
              score: function() {
                return $(this).attr('data-score');
              }
            });
          };
        })(this));
      };

      RatyVoteForm.prototype._showDone = function(data) {
        var $iconDone;
        this._showRating();
        $iconDone = $(this.currentTarget).parent().find(this.selectors.iconDone);
        $iconDone.show((function(_this) {
          return function() {
            return setTimeout(function() {
              return $iconDone.hide();
            }, 500);
          };
        })(this));
      };

      RatyVoteForm.prototype._showRating = function() {
        $.ajax({
          url: this.options.actionUrl,
          type: 'post',
          dataType: 'json',
          data: this.dataShowRating
        }).done((function(_this) {
          return function(data) {
            _this.$elsShowRating.find(_this.selectors.raty.all).raty('set', {
              score: data.object.rating
            });
            _this.$elsShowRating.find(_this.selectors.rating).html(data.object.rating);
            _this.$elsShowRating.find(_this.selectors.usersCount).html(data.object.users_count);
          };
        })(this)).fail(this._showFail);
      };

      return RatyVoteForm;

    })(Module);
    UpVoteForm = (function(superClass) {
      extend(UpVoteForm, superClass);

      UpVoteForm.include(VoteForm);

      function UpVoteForm(el, options) {
        this._showDone = bind(this._showDone, this);
        this.options = $.extend({}, this.defaults, options);
        this.selectors = this.options.selectors;
        this.$el = $(el);
        this.$upvotes = this.$el.find(this.selectors.upvote);
        this.data = {
          action: 'record/record_multiple',
          form: this.$el.data('form'),
          thread: this.$el.data('thread'),
          fields: []
        };
      }

      UpVoteForm.prototype.init = function() {
        if (!jQuery().upvote && !UpVoteForm.init) {
          UpVoteForm.init = true;
          document.write('<script src="' + this.options.vendorUrl + 'jquery-upvote/lib/jquery.upvote.js"></script>');
        }
        return this._ready();
      };

      UpVoteForm.prototype._ready = function() {
        return $(document).ready((function(_this) {
          return function() {
            var self;
            self = _this;
            return _this.$upvotes.upvote({
              callback: function() {
                var value;
                value = 0;
                if (this.upvoted) {
                  value = 1;
                }
                if (this.downvoted) {
                  value = -1;
                }
                return self._sendRecors([
                  {
                    id: this.id,
                    value: value
                  }
                ]);
              }
            });
          };
        })(this));
      };

      UpVoteForm.prototype._showDone = function(data) {};

      return UpVoteForm;

    })(Module);
    return $.fn.extend({
      voteForm: function() {
        var args, option;
        option = arguments[0], args = 2 <= arguments.length ? slice.call(arguments, 1) : [];
        return this.each(function() {
          var $this, voteform, widget;
          $this = $(this);
          voteform = $this.data('VoteForm');
          if (!voteform) {
            widget = $this.data('widget');
            switch (widget) {
              case 'raty':
                $this.data('VoteForm', (voteform = new RatyVoteForm(this, option)));
                break;
              case 'upvote':
                $this.data('VoteForm', (voteform = new UpVoteForm(this, option)));
                break;
              default:
                $this.data('VoteForm', (voteform = new RatyVoteForm(this, option)));
            }
            voteform.init();
          }
          if (typeof option === 'string') {
            return voteform[option].apply(voteform, args);
          }
        });
      }
    });
  })(window.jQuery, window);

  $('.vtf').voteForm(VoteFormsConfig);

}).call(this);

//# sourceMappingURL=voteforms.js.map
