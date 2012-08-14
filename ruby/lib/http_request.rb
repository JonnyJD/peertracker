module Peertracker
  class HTTPRequest
    def initialize(host, path = "")
      @host = host
      @path = path
    end

    def perform_request
      uri = URI("http://#{@host}#{@path}/")
      response = Net::HTTP.get_response(uri)
      response.body
    end
  end
end
