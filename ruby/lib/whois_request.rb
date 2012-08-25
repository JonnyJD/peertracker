module Peertracker
  class WhoisRequest
    def get_as_info(ip_address)
      `whois -h whois.radb.net #{ip_address}`
    end

    def get_country_info(ip_address)
      `whois -h whois.lacnic.net #{ip_address}`
    end
  end
end
