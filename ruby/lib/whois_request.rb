module Peertracker
  class WhoisRequest
    def get_as_info(ip_address)
      `whois -m #{ip_address}`
    end

    def get_country_info(ip_address)
      `whois -l #{ip_address}`
    end
  end
end
