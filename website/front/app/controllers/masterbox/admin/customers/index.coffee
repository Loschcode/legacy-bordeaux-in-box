# Require the controller library of Gotham
Controller = require 'core/controller'
Config = require 'config'


class Index extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    $('table').DataTable
      length: false
      language: Config.datatable.language.fr
      ajax: $('table').data('request')
      deferRender: true
      columns: [
        { data: "id" }
        { data: "full_name" }
        { data: "email" }
        { data: "phone_format" }
        {
          sortable: false,
          render: (data, type, full, meta) =>

            datas = 
              link_edit: $('table').data('edit') + '/' + full.id

            return @view('masterbox.admin.customers.actions', datas)
        }
      ]

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->



# Export
module.exports = Index
