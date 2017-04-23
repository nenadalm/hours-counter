%token day [a-z]+:
%token end_of_block =+
%token time_interval [0-9]{1,2}:[0-9]{1,2}\s-\s[0-9]{1,2}:[0-9]{1,2}
%token description (\*\s[^\n]*)
%token end_of_statement ;\s*
%token end_of_line \n

#result:
    day_records()+

#day_records:
    day_line()
    end_of_block_line()
    (record() <end_of_line>*)+

#record:
    time_interval_line()
    description_lines()
    <end_of_line>*

day_line:
    <day> <end_of_line>

end_of_block_line:
    <end_of_block> <end_of_line>

time_interval_line:
    <time_interval> (<end_of_statement> <time_interval>)* <end_of_line>

description_lines:
    (<description> <end_of_line>)+
