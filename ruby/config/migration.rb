Bundler.require
require File.join(File.dirname(__FILE__), "database.rb")

class AddASInfoToPeers < ActiveRecord::Migration
  def up
    add_column :pt_peers, :as_code, :string
    add_column :pt_peers, :country, :string
  end

  def down
    remove_column :pt_peers, :as_code
    remove_column :pt_peers, :country
  end
end

AddASInfoToPeers.new.up
