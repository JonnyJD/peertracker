class ASParser
  def parse_response(response)
    [parse_as(response),parse_country(response)]
  end

  def parse_as(response)
    begin
      response.scan(/Provider:<\/b><p>(\w*)/)[0][0]
    rescue => e
      puts "Could not parse AS"
      nil
    end
  end

  def parse_country(response)
    begin
      response.scan(/Location:<\/b><p>(.*)<p>/)[0][0].split(",").last.strip
    rescue => e
      puts "Could not parse Country"
      nil
    end
  end
end
