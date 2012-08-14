Bundler.require
require File.join(File.dirname(__FILE__),'../lib/as_info.rb')

get '/as_info/:ip_address.json' do |ip_address|
  response = Peertracker::ASInfo.new.set_as_info_for_ip(ip_address)
  {:as_code => response[0], :country => response[1]}.to_json
end
