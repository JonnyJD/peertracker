Bundler.require
require File.join(File.dirname(__FILE__), "../config/database.rb")
require File.join(File.dirname(__FILE__), "./model/peer.rb")
require File.join(File.dirname(__FILE__), "./url_creator.rb")
require File.join(File.dirname(__FILE__), "./http_request.rb")
require File.join(File.dirname(__FILE__), "./whois_request.rb")
require File.join(File.dirname(__FILE__), "./as_parser.rb")

module Peertracker
  class ASInfo
    def set_as_info_for_ip(ip_address)
      raw_code = WhoisRequest.new.get_as_info(ip_address)
      raw_country = WhoisRequest.new.get_country_info(ip_address)
      ASParser.new.parse_response(raw_code, raw_country)
    end

    class << self
      def set_as_info
        Peer.without_as_info.each do |peer|
          begin
            puts "Parsing #{peer.ip}"
          peer.as_code, peer.country = self.new.set_as_info_for_ip(peer.ip)
          peer.save
          rescue => e
            puts "Could not fetch AS for #{peer.ip}"
            puts "#{e.to_s}"
          end
        end
      end
    end
  end
end
