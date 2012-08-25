class ASParser
  def parse_response(raw_code, raw_country)
    [parse_as(raw_code),parse_country(raw_country)]
  end

  def parse_as(raw_code)
    begin
      raw_code.scan(/origin:\s*([A-Z0-9]*)\s/i)[0][0]
    rescue => e
      puts "Could not parse AS"
      nil
    end
  end

  def parse_country(raw_country)
    begin
      raw_country.scan(/country:\s*([A-Z0-9]*)\s/i)[0][0]
    rescue => e
      puts "Could not parse Country"
      nil
    end
  end
end
