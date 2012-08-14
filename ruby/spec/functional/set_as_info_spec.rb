require File.join(File.dirname(__FILE__), "../../spec/spec_helper.rb")

describe "ASInfo" do 
  context "when given IP address" do
    describe "sets as info for ip address" do
      let(:ip_address) {"12.12.12.12"}

      it "stores as_info to database" do
        ASInfo.new.set_as_info_for_ip(ip_address)
        Peer.find_by_ip_address(ip_address).as.should_not be_null
      end
    end
  end

  describe ".set_as_info" do
    it "stores as info for all addresses"
      ASInfo.set_as_info
      Peer.all.each {|peer| peer.as.should_not be_null}
  end
end
