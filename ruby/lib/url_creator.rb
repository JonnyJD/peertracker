require "net/http"

module Peertracker
  class URLCreator
    def initialize(url_host, ip_address)
      @ip_address = ip_address
      @url_host = url_host
    end

    def create_subdomain
      "%02x%02x%02x%02x" % @ip_address.scan(/(.*)\.(.*)\.(.*)\.(.*)/)[0]
    end

    def create_url
      "#{create_subdomain}.#{@url_host}"
    end
  end
end
