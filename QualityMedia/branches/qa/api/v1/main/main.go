package main
import (
  "code.google.com/p/gorest"
  "net/http"
  "net/http/fcgi"
  "flag"
  "net"
  "log"
  "database/sql"
  _ "github.com/go-sql-driver/mysql"
)

var (
    fcgiPort = flag.String("p", ":8081", "Port to listen for incoming FCGI requests")
)

type SearchParameters struct {
  RelevantToClientBranchId int
  ContainsAuthDataId int
  DefinedByUserId int
}

type YelpSearchParameters struct {
  YelpBusinessName string
  YelpBusinessLocation string
  Parameters SearchParameters
}

type Review struct {
  PublishedOnTimestamp int
  HandledByCustomerServiceAgent int
  MatchesSearchParameters SearchParameters
  HasRating int
  HasMetadataObject int
  HasURL string
  PublishedByAuthorURL string
  HasPostContents string
}

// Reference for this: https://code.google.com/p/gorest/
// More: https://code.google.com/p/gorest/wiki/GettingStarted
type APIService struct {
  gorest.RestService `root:"/v1/" consumes:"application/json" produces:"application/json"`
  searchParametersInsert gorest.EndPoint `method:"PUT" 
                                          path:"/searchParameters" 
                                          postdata:"SearchParameters"
                                          `
  searchParametersSelect gorest.EndPoint `method:"GET"
                                          path:"/searchParameters"
                                          output:"SearchParameters"
                                          `
  db *sql.DB
}

func (serv APIService) SearchParametersSelect() SearchParameters {
  k := SearchParameters{
    RelevantToClientBranchId: 12,
    ContainsAuthDataId: 34,
    DefinedByUserId: 52,
  }
  return k
}
func (serv APIService) SearchParametersInsert(s SearchParameters) {
  // return "Insert parameters"

	res, err := serv.db.Exec(`
      INSERT INTO search_parameters 
      SET relevantToClientBranchId = ?
        , containsAuthDataId = ?
        , definedByUserId = ?
  `, s.RelevantToClientBranchId, s.ContainsAuthDataId, s.DefinedByUserId)
	if err != nil { panic (err.Error()) }

  lastId, err := res.LastInsertId()
  if err != nil { panic (err.Error()) }

  serv.ResponseBuilder().SetResponseCode(201).Location("/v1/searchParameters/" + string(lastId))
}

func main() {

	// db, err := sql.Open("mysql", "master_qm:MLcMrD7ea6yt@tcp(qmdbmdev.cbmovomozg13.us-west-2.rds.amazonaws.com)/qmdb?charset=utf8")
	db, err := sql.Open("mysql", "master_qm:MLcMrD7ea6yt@tcp(qmdbmdev.cbmovomozg13.us-west-2.rds.amazonaws.com:3306)/qmdb?charset=utf8")
	if err != nil {
		panic(err.Error())
	}
	defer db.Close()

  listener, err := net.Listen("tcp", *fcgiPort)
  if err != nil {
    log.Fatalf ("Unable to start fcgi listener. Cause: %v", err)
  }

  apiService := new(APIService)
  apiService.db = db
  gorest.RegisterService(apiService)
  http.Handle("/", gorest.Handle())

  err = fcgi.Serve(listener, nil)
  if err != nil {
    log.Fatalf("Error in the FCGI package. Cause: %v", err)
  }
}
