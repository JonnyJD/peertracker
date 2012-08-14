class Peer < ActiveRecord::Base
  self.table_name = "pt_peers"
  self.primary_key = "peer_id"
  scope :without_as_info,  lambda{ where("pt_peers.as_code IS NULL") }
end
